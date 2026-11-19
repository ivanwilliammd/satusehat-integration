<?php

namespace Satusehat\Integration\Tests\HL7v2;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\HL7v2\Ack;
use Satusehat\Integration\HL7v2\Encoding;
use Satusehat\Integration\HL7v2\Message;

final class MessageTest extends TestCase
{
    private const ADT = "MSH|^~\\&|SIMRS|RS-01|SATUSEHAT|KEMKES|20261118120000||ADT^A01^ADT_A01|MSG00001|P|2.5.1\r"
        . "EVN|A01|20261118115900\r"
        . "PID|1||0001234567890^^^NIK^NIK||ANGGRAENI^SITI^^^\r"
        . "PV1|1|I|WARD01^101^A\r"
        . "OBX|1|NM|8867-4^Heart rate^LN||78|/min|||||F\r";

    public function testParsesSegmentsAndFields(): void
    {
        $msg = Message::parse(self::ADT);

        self::assertCount(5, $msg->segments());
        self::assertSame('ADT', $msg->messageType());
        self::assertSame('A01', $msg->triggerEvent());
        self::assertSame('MSG00001', $msg->controlId());
        self::assertSame('2.5.1', $msg->version());
        self::assertSame('ANGGRAENI', $msg->get('PID.5.1'));
        self::assertSame('SITI', $msg->get('PID.5.2'));
        self::assertSame('0001234567890', $msg->get('PID.3.1'));
        self::assertSame('NIK', $msg->get('PID.3.5'));
        self::assertSame('78', $msg->get('OBX.5'));
    }

    public function testRoundTripsByteForByte(): void
    {
        self::assertSame(self::ADT, Message::parse(self::ADT)->toString());
    }

    public function testDecodesEscapeSequences(): void
    {
        $msg = Message::parse("MSH|^~\\&|A|B|C|D|20260101||ADT^A08|1|P|2.5.1\r"
            . "PID|1||123||SMITH\\S\\JOHN\\F\\JR\\T\\II\r");

        self::assertSame('SMITH^JOHN|JR&II', $msg->get('PID.5.1'));
        self::assertSame('SMITH^JOHN|JR&II', $msg->get('PID.5'));
    }

    public function testWritesWithEscaping(): void
    {
        $msg = Message::parse(self::ADT);
        $msg->set('PID.5.1', 'O\'BRIEN&SONS');

        $er7 = $msg->toString();
        self::assertStringContainsString('O\'BRIEN\\T\\SONS', $er7);

        $again = Message::parse($er7);
        self::assertSame('O\'BRIEN&SONS', $again->get('PID.5.1'));
    }

    public function testRejectsMessageWithoutMsh(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Message::parse("PID|1||123\r");
    }

    public function testHandlesLfOnlyLineEndings(): void
    {
        $msg = Message::parse("MSH|^~\\&|A|B|C|D|20260101||ORU^R01|9|P|2.5.1\nPID|1||123\n");

        self::assertSame('ORU', $msg->messageType());
        self::assertCount(2, $msg->segments());
    }

    public function testAckEchoesControlIdAndVerdict(): void
    {
        $ack = Ack::build(Message::parse(self::ADT));

        self::assertSame('ACK', $ack->messageType());
        self::assertSame('AA', $ack->get('MSA.1'));
        self::assertSame('MSG00001', $ack->get('MSA.2'));
        self::assertSame('2.5.1', $ack->version());
    }

    public function testErrorAckCarriesReason(): void
    {
        $ack = Ack::error(Message::parse(self::ADT), 'PID wajib ada');

        self::assertSame('AE', $ack->get('MSA.1'));
        self::assertSame('PID wajib ada', $ack->get('MSA.3'));
    }

    public function testCharsetIsReportedNotGuessed(): void
    {
        self::assertSame('ASCII', Message::parse(self::ADT)->charset());
        self::assertSame('UTF-8', Encoding::charsetOf('UNICODE UTF-8'));
        self::assertSame('ISO-8859-1', Encoding::charsetOf('8859/1'));
    }
}
