<?php

namespace Satusehat\Integration\Tests\HL7v2;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\HL7v2\Batch;
use Satusehat\Integration\HL7v2\Message;

final class BatchTest extends TestCase
{
    private const TWO = "FHS|^~\\&|SIMRS|LAB|20261118120000\r"
        . "BHS|^~\\&|SIMRS|LAB|20261118120000\r"
        . "MSH|^~\\&|SIMRS|RS-01|LAB|RS-01|20261118120000||ADT^A01|B1|P|2.5.1\rPID|1||111||A^A\rPV1|1|I|W1\r"
        . "MSH|^~\\&|SIMRS|RS-01|LAB|RS-01|20261118120100||ORU^R01|B2|P|2.5.1\rPID|1||222||B^B\rOBR|1|O|L|CBC^CBC^L\rOBX|1|NM|718-7^Hgb^LN||13|g/dL||||F\r"
        . "BTS|2\rFTS|1\r";

    public function testSplitsBatchIntoMessages(): void
    {
        $messages = Batch::parse(self::TWO);

        self::assertCount(2, $messages);
        self::assertSame('ADT', $messages[0]->messageType());
        self::assertSame('B1', $messages[0]->controlId());
        self::assertSame('ORU', $messages[1]->messageType());
        self::assertSame('13', $messages[1]->get('OBX.5'));
    }

    public function testDeclaredCountMatchesWhatWasParsed(): void
    {
        self::assertSame(2, Batch::declaredCount(self::TWO));
        self::assertSame(2, count(Batch::parse(self::TWO)));
    }

    public function testWrapRoundTrips(): void
    {
        $messages = Batch::parse(self::TWO);
        $wrapped = Batch::wrap($messages);
        $again = Batch::parse($wrapped);

        self::assertCount(2, $again);
        self::assertSame($messages[1]->controlId(), $again[1]->controlId());
        self::assertSame(2, Batch::declaredCount($wrapped));
    }

    public function testEmptyBatchIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Batch::parse("   ");
    }

    /** A single message is not a batch; parse() must still return it exactly once. */
    public function testSingleMessageInsideEnvelope(): void
    {
        $one = "FHS|^~\\&|A|B|20260101\rBHS|^~\\&|A|B|20260101\r"
            . "MSH|^~\\&|A|B|C|D|20260101||ADT^A01|S1|P|2.5.1\rPID|1||1\rBTS|1\rFTS|1\r";

        $messages = Batch::parse($one);
        self::assertCount(1, $messages);
        self::assertSame('S1', $messages[0]->controlId());
    }

    /** Without the envelope the same bytes are still a sequence of messages: senders omit FHS/BHS. */
    public function testBatchWithoutEnvelope(): void
    {
        $bare = "MSH|^~\\&|A|B|C|D|20260101||ADT^A01|X1|P|2.5.1\rPID|1||1\r"
            . "MSH|^~\\&|A|B|C|D|20260101||ADT^A01|X2|P|2.5.1\rPID|1||2\r";

        self::assertCount(2, Batch::parse($bare));
    }
}
