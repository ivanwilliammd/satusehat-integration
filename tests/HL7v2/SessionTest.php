<?php

namespace Satusehat\Integration\Tests\HL7v2;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\HL7v2\Ack;
use Satusehat\Integration\HL7v2\Client;
use Satusehat\Integration\HL7v2\Message;
use Satusehat\Integration\HL7v2\Mllp;

final class SessionTest extends TestCase
{
    private function message(string $controlId): Message
    {
        return Message::parse("MSH|^~\\&|A|B|C|D|20260101||ORU^R01|{$controlId}|P|2.5.1\r"
            . "PID|1||1\rOBR|1|O|L|CBC^CBC^L\rOBX|1|NM|718-7^Hgb^LN||13|g/dL||||F\r");
    }

    public function testSessionSendsEveryMessageOverOneConnection(): void
    {
        $messages = [$this->message('S1'), $this->message('S2'), $this->message('S3')];

        [$client, $server] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        self::assertIsResource($client);
        // Pre-load three ACKs: the client writes, then reads whatever the peer has queued.
        foreach ($messages as $m) {
            fwrite($server, Mllp::frame(Ack::build($m)->toString()));
        }

        $results = (new Client('unused:0'))->sessionOnStream($client, $messages);

        self::assertCount(3, $results);
        self::assertSame(['S1', 'S2', 'S3'], array_map(static fn ($r): string => $r->controlId, $results));
        foreach ($results as $r) {
            self::assertSame(Ack::ACCEPT, $r->code);
        }
        fclose($client);
        fclose($server);
    }
}
