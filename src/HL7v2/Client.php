<?php

declare(strict_types=1);

namespace Satusehat\Integration\HL7v2;

use RuntimeException;

/**
 * MLLP client for analyzers and LIS endpoints.
 *
 * Two rules that matter operationally:
 *
 * 1. **Retry the transport, never the verdict.** A timeout or a closed socket is worth retrying; an
 *    `AE`/`AR` means the receiver understood and refused. Retrying an application rejection is how a
 *    queue turns one bad message into a thousand.
 * 2. **Verify the correlation.** MSA-2 must echo MSH-10. An ACK whose control id does not match is a
 *    stale or misrouted reply, and treating it as success silently loses the message.
 */
final class Client
{
    public function __construct(
        private string $address,
        private float $timeout = 10.0,
        private int $retries = 2,
        private float $backoffSeconds = 0.5
    ) {
    }

    public function send(Message $message): SendResult
    {
        $started = microtime(true);
        $lastError = null;

        for ($attempt = 1; $attempt <= $this->retries + 1; $attempt++) {
            try {
                $stream = $this->connect();
                try {
                    $result = $this->exchange($stream, $message);
                    $result->attempts = $attempt;
                    $result->latencyMs = round((microtime(true) - $started) * 1000, 1);

                    return $result;
                } finally {
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }
            } catch (RuntimeException $e) {
                $lastError = $e;
                if ($attempt <= $this->retries) {
                    usleep((int) ($this->backoffSeconds * 1_000_000 * $attempt));
                }
            }
        }

        throw new RuntimeException(
            "Gagal mengirim ke {$this->address} setelah {$this->retries} percobaan ulang: " . $lastError?->getMessage(),
            0,
            $lastError
        );
    }

    /**
     * Sends one message over an already-open stream and reads one ACK.
     *
     * @param resource $stream
     */
    public function exchange($stream, Message $message): SendResult
    {
        if (!Mllp::write($stream, $message->toString())) {
            throw new RuntimeException('Koneksi ditutup peer saat menulis pesan.');
        }

        $ackRaw = Mllp::read($stream, 1 * 1024 * 1024);
        if ($ackRaw === null) {
            throw new RuntimeException('Tidak ada ACK dalam batas waktu.');
        }

        return $this->interpret($message, $ackRaw);
    }

    /** Exposed so callers that already hold a socket (a daemon, a test) can reuse the parsing. */
    public function interpret(Message $sent, string $ackRaw): SendResult
    {
        $ack = Message::parse($ackRaw);

        if ($ack->messageType() !== 'ACK') {
            throw new RuntimeException("Balasan bukan ACK melainkan {$ack->messageType()}.");
        }

        $echo = $ack->get('MSA.2');
        if ($echo !== '' && $echo !== $sent->controlId()) {
            throw new RuntimeException(
                "MSA-2 ({$echo}) tidak cocok dengan MSH-10 ({$sent->controlId()}) — ACK basi atau salah rute."
            );
        }

        $code = strtoupper($ack->get('MSA.1')) ?: Ack::ERROR;

        return new SendResult($code, $echo, $ack->get('MSA.3'), $ack, 1, 0.0);
    }

    /** @return resource */
    private function connect()
    {
        $errno = 0;
        $errstr = '';
        $stream = @stream_socket_client(
            'tcp://' . $this->address,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT
        );

        if (!is_resource($stream)) {
            throw new RuntimeException("Tidak bisa menghubungi {$this->address}: {$errstr} ({$errno})");
        }
        stream_set_timeout($stream, (int) $this->timeout);

        return $stream;
    }
}
