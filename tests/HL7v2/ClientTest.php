<?php

namespace Satusehat\Integration\Tests\HL7v2;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Satusehat\Integration\HL7v2\Ack;
use Satusehat\Integration\HL7v2\Client;
use Satusehat\Integration\HL7v2\Message;
use Satusehat\Integration\HL7v2\Mllp;

final class ClientTest extends TestCase
{
    private const ADT = "MSH|^~\\&|SIMRS|RS-01|LAB|RS-01|20261118120000||ADT^A01|MSG42|P|2.5.1\r"
        . "EVN|A01|20261118115900\rPID|1||123||X^Y||19800101|F\rPV1|1|I|WARD01\r";

    public function testExchangeReadsAckAndEchoesControlId(): void
    {
        $msg = Message::parse(self::ADT);
        [$client, $server] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        self::assertIsResource($client);

        fwrite($server, Mllp::frame(Ack::build($msg)->toString()));

        $result = (new Client('unused:0'))->exchange($client, $msg);

        self::assertSame(Ack::ACCEPT, $result->code);
        self::assertSame('MSG42', $result->controlId);
        self::assertTrue($result->accepted());
        fclose($client);
        fclose($server);
    }

    public function testApplicationErrorIsNotAnException(): void
    {
        $msg = Message::parse(self::ADT);
        [$client, $server] = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        fwrite($server, Mllp::frame(Ack::error($msg, 'PID tidak valid')->toString()));

        $result = (new Client('unused:0'))->exchange($client, $msg);

        self::assertSame(Ack::ERROR, $result->code);
        self::assertFalse($result->accepted());
        self::assertSame('PID tidak valid', $result->text);
        fclose($client);
        fclose($server);
    }

    /** A reply for a different message must never be accepted as this message's ACK. */
    public function testMismatchedControlIdIsRejected(): void
    {
        $msg = Message::parse(self::ADT);
        $stale = Ack::build($msg)->set('MSA.2', 'OTHER123');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/tidak cocok/');

        (new Client('unused:0'))->interpret($msg, $stale->toString());
    }

    public function testRetriesThenFailsWithClearMessage(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/setelah 1 percobaan ulang/');

        (new Client('127.0.0.1:1', 0.3, 1, 0.01))->send(Message::parse(self::ADT));
    }
}
