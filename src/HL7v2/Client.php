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
     * Sends several messages over one TCP connection, reconnecting only when the link actually died.
     *
     * Persistent MLLP is what analyzers expect: a new connection per result is slow and, on some
     * instruments, rejected outright. The caller gets one result per message, in order, so a failure
     * on message 3 does not discard the two that were accepted.
     *
     * @param list<Message> $messages
     * @return list<SendResult>
     */
    public function session(array $messages): array
    {
        return $this->sessionOnStream(null, $messages);
    }

    /**
     * Same as session(), but reuses a stream the caller already holds. Passing null means "connect
     * yourself", which is what session() does; tests pass a socket pair, and a long-lived daemon can
     * pass its own socket.
     *
     * @param resource|null $stream
     * @param list<Message> $messages
     * @return list<SendResult>
     */
    public function sessionOnStream($stream, array $messages): array
    {
        $results = [];
        $reader = new MllpReader();
        // Ownership: a stream the caller handed us is closed by the caller. Only a connection this
        // method opened itself is closed here.
        $owned = !is_resource($stream);

        try {
            foreach ($messages as $msg) {
                for ($attempt = 1; ; $attempt++) {
                    try {
                        if (!is_resource($stream)) {
                            $stream = $this->connect();
                            $reader = new MllpReader();
                        }
                        $results[] = $this->exchangeWith($stream, $reader, $msg);
                        break;
                    } catch (RuntimeException $e) {
                        if (is_resource($stream) && $owned) {
                            fclose($stream);
                        }
                        if (is_resource($stream) && $owned) {
                            $stream = null;
                        }
                        if ($attempt > $this->retries) {
                            throw new RuntimeException(
                                'Koneksi MLLP gagal pada pesan ke-' . (count($results) + 1) . ': ' . $e->getMessage(),
                                0,
                                $e
                            );
                        }
                        usleep((int) ($this->backoffSeconds * 1_000_000 * $attempt));
                    }
                }
            }
        } finally {
            if ($owned && is_resource($stream)) {
                fclose($stream);
            }
        }

        return $results;
    }

    /**
     * Sends one message over an already-open stream and reads one ACK.
     *
     * @param resource $stream
     */
    public function exchange($stream, Message $message): SendResult
    {
        return $this->exchangeWith($stream, new MllpReader(), $message);
    }

    /** @param resource $stream */
    private function exchangeWith($stream, MllpReader $reader, Message $message): SendResult
    {
        if (!Mllp::write($stream, $message->toString())) {
            throw new RuntimeException('Koneksi ditutup peer saat menulis pesan.');
        }

        $ackRaw = $reader->next($stream);
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
