<?php

declare(strict_types=1);

namespace Satusehat\Integration\Tests\FHIR\Parser;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Parser\PaymentNoticeParser;

/**
 * PaymentNoticeParser is checked against the official example payload, element by element: every accessor
 * must return exactly what the payload holds. A renamed or dropped key fails here, which is the
 * failure mode a "parser" can realistically have.
 */
class PaymentNoticeParserTest extends TestCase
{
    public function test_setiap_aksesor_mengembalikan_elemen_yang_sama_dengan_contoh_resmi(): void
    {
        $jalur = __DIR__.'/../../Fixtures/satusehat/PaymentNotice.json';
        $this->assertFileExists($jalur);
        $data = json_decode(file_get_contents($jalur), true);
        $this->assertSame('PaymentNotice', $data['resourceType']);

        $parser = new PaymentNoticeParser($data);

        $this->assertSame($data, $parser->getRaw());
        $this->assertSame($data['resourceType'], $parser->getResourceType(), 'elemen resourceType');
        $this->assertSame($data['status'], $parser->getStatus(), 'elemen status');
        $this->assertSame($data['request'], $parser->getRequest(), 'elemen request');
        $this->assertSame($data['response'], $parser->getResponse(), 'elemen response');
        $this->assertSame($data['created'], $parser->getCreated(), 'elemen created');
        $this->assertSame($data['paymentDate'], $parser->getPaymentDate(), 'elemen paymentDate');
        $this->assertSame($data['provider'], $parser->getProvider(), 'elemen provider');
        $this->assertSame($data['payee'], $parser->getPayee(), 'elemen payee');
        $this->assertSame($data['recipient'], $parser->getRecipient(), 'elemen recipient');
        $this->assertSame($data['amount'], $parser->getAmount(), 'elemen amount');
        $this->assertSame($data['payment'], $parser->getPayment(), 'elemen payment');
        $this->assertSame($data['paymentStatus'], $parser->getPaymentStatus(), 'elemen paymentStatus');
    }
}
