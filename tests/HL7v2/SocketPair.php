<?php

namespace Satusehat\Integration\Tests\HL7v2;

/**
 * Two connected streams for the MLLP tests.
 *
 * `stream_socket_pair()` does not exist on Windows (PHP fails with error 10042 — unsupported option), which
 * turned the whole Windows CI job red while the same tests passed on Linux. Skipping them on Windows would
 * hide a platform we ship to, so the fallback is a real loopback TCP pair: same read/write semantics, supported
 * everywhere PHP runs.
 *
 * Set SATUSEHAT_TEST_SOCKET_PAIR=loopback to force the fallback on Linux — that is how the Windows path gets
 * exercised without a Windows machine.
 */
final class SocketPair
{
    /**
     * @return array{0: resource, 1: resource}
     */
    public static function make(): array
    {
        $forced = getenv('SATUSEHAT_TEST_SOCKET_PAIR');
        $usePair = PHP_OS_FAMILY !== 'Windows' && $forced !== 'loopback';

        if ($usePair && function_exists('stream_socket_pair')) {
            $pair = @stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);

            if (is_array($pair) && $pair[0] !== false && $pair[1] !== false) {
                return [$pair[0], $pair[1]];
            }
        }

        $server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
        if ($server === false) {
            throw new \RuntimeException("cannot open a loopback socket: {$errstr} ({$errno})");
        }

        $name = stream_socket_get_name($server, false);
        $client = stream_socket_client('tcp://' . $name, $errno, $errstr, 5);

        if ($client === false) {
            fclose($server);

            throw new \RuntimeException("cannot connect to the loopback socket: {$errstr} ({$errno})");
        }

        $peer = stream_socket_accept($server, 5);
        fclose($server);

        if ($peer === false) {
            fclose($client);

            throw new \RuntimeException('the loopback socket never accepted a connection');
        }

        return [$client, $peer];
    }
}
