<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderClaim;
use Satusehat\Integration\Builder\PayloadBuilderClaimResponse;
use Satusehat\Integration\Builder\PayloadBuilderImagingStudy;
use Satusehat\Integration\Builder\PayloadBuilderInvoice;

/**
 * Empat titik pemanggilan memakai jalur BER-VARIABEL (`item/{$idx}/location`,
 * `addItem/{$idx}/adjudication`, `series/{$idx}/instance`, `lineItem/{$idx}/priceComponent`).
 * Penjaga statis `NestedPathSweepTest` hanya MENGHITUNG titik itu — ia tidak pernah
 * membuktikan hasilnya mendarat benar. Uji ini yang membuktikan, sekaligus menjaga satu
 * cacat yang tidak terlihat dari JSON: indeks yang tidak menunjuk elemen mana pun membuat
 * PHP menyusun array ber-kunci rapat dari 1 atau -1, dan `json_encode` mengubahnya menjadi
 * OBJEK (`{"1": …}`) — FHIR menuntut LIST di posisi itu, dan server menolaknya sebagai
 * tipe salah tanpa menyebut sebabnya.
 */
class NestedPathVariablePathTest extends TestCase
{
    /** @return array<int|string, mixed> */
    private function nodeAt(array $payload, string $path)
    {
        $node = $payload;
        foreach (explode('.', $path) as $seg) {
            $this->assertIsArray($node, 'jalur '.$path.' tidak ada');
            $node = $node[$seg] ?? null;
        }
        $this->assertIsArray($node, 'jalur '.$path.' tidak ada');

        return $node;
    }

    /** Kunci numerik harus berurutan 0..n-1 — kalau tidak, JSON-nya objek, bukan list. */
    private function assertIsList(array $arr, string $label): void
    {
        $this->assertSame(range(0, count($arr) - 1), array_keys($arr), $label.' bukan list (kunci tidak 0..n-1)');
    }

    public function test_claim_item_location_lands_on_the_indexed_item(): void
    {
        $builder = (new PayloadBuilderClaim())
            ->addItem(1, 'http://sys', 'A01', 'Konsultasi')
            ->addItem(2, 'http://sys', 'A02', 'Tindakan')
            ->addItemLocation(1, 'http://sys', 'LOC-2', 'Ruang 2');

        $item = $this->nodeAt($builder->json(), 'item');
        $this->assertIsList($item, 'Claim.item');
        $this->assertArrayNotHasKey('location', $item[0], 'indeks 0 tidak boleh ikut terisi');
        $this->assertSame('LOC-2', $item[1]['location'][0]['coding'][0]['code']);
    }

    public function test_claim_response_adjudication_lands_on_the_last_added_item(): void
    {
        $builder = (new PayloadBuilderClaimResponse())
            ->addAddItem(1, 'http://sys', 'A01')
            ->addAddItemAdjudication(1, 'http://sys', 'eligible', 100000.0);

        $addItem = $this->nodeAt($builder->json(), 'addItem');
        $this->assertIsList($addItem, 'ClaimResponse.addItem');
        $this->assertSame('eligible', $addItem[0]['adjudication'][0]['category']['coding'][0]['code']);
        $this->assertSame(100000.0, $addItem[0]['adjudication'][0]['value']);
    }

    public function test_imaging_study_instance_lands_on_the_indexed_series(): void
    {
        $builder = (new PayloadBuilderImagingStudy())
            ->addSeries('1.2.3', 'http://dicom', 'CT', 'CT')
            ->addSeries('1.2.4', 'http://dicom', 'MR', 'MR')
            ->addSeriesInstance(1, '1.2.4.1', 'http://dicom', 'SOP', 'SOP');

        $series = $this->nodeAt($builder->json(), 'series');
        $this->assertIsList($series, 'ImagingStudy.series');
        $this->assertArrayNotHasKey('instance', $series[0]);
        $this->assertSame('1.2.4.1', $series[1]['instance'][0]['uid']);
    }

    public function test_invoice_price_component_lands_on_the_indexed_line_item(): void
    {
        $builder = (new PayloadBuilderInvoice())
            ->addLineItem(1, 'http://sys', 'SVC-1', 'Layanan 1')
            ->addLineItemPriceComponent(0, 'base', 250000.0)
            ->addLineItemPriceComponent(0, 'discount', 25000.0, 'DISC');

        $lineItem = $this->nodeAt($builder->json(), 'lineItem');
        $this->assertIsList($lineItem, 'Invoice.lineItem');
        $this->assertCount(2, $lineItem[0]['priceComponent']);
        $this->assertSame('discount', $lineItem[0]['priceComponent'][1]['type']);
        $this->assertSame('DISC', $lineItem[0]['priceComponent'][1]['code']);
    }

    /**
     * CACAT yang dijaga: `addAddItemAdjudication()` menghitung indeks dari
     * `count($this->data['addItem'] ?? []) - 1`. Tanpa `addItem` lebih dulu hasilnya **-1**,
     * dan payload memuat `{"addItem":{"-1":{"adjudication":[…]}}}` — bukan FHIR.
     * Indeks yang tidak menunjuk elemen harus DITOLAK, bukan diterima diam-diam.
     */
    public function test_adjudication_without_any_add_item_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new PayloadBuilderClaimResponse())->addAddItemAdjudication(1, 'http://sys', 'eligible', 1000.0)->json();
    }

    public function test_series_instance_on_missing_series_is_rejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new PayloadBuilderImagingStudy())->addSeriesInstance(3, '1.2.3.4', 'http://dicom', 'SOP', 'SOP')->json();
    }

    public function test_same_guard_applies_to_set_paths(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new PayloadBuilderImagingStudy())->setSeriesStarted(2, '2026-01-01T00:00:00+07:00')->json();
    }

    /** Pesan galat harus menyebut jalurnya — tanpa itu pemanggil tidak tahu indeks mana yang salah. */
    public function test_error_message_names_the_path(): void
    {
        try {
            (new PayloadBuilderClaimResponse())->addAddItemAdjudication(1, 'http://sys', 'eligible')->json();
            $this->fail('seharusnya melempar');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('addItem', $e->getMessage());
            $this->assertStringContainsString('-1', $e->getMessage());
        }
    }
}
