<?php

namespace Satusehat\Integration\Tests\Conformance;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\Builder;
use Satusehat\Integration\Conformance\R4ElementGuard;

/**
 * Uji penjaga elemen R4.
 *
 * Dua hal yang dijaga: penjaganya benar-benar menangkap elemen asing (bukan selalu hijau), dan
 * mode ketat tidak menyala tanpa diminta — mengubah perilaku bawaan akan menggagalkan pemanggil
 * lama yang memakai setter generik.
 */
class R4ElementGuardTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv('SATUSEHAT_STRICT_R4');
        parent::tearDown();
    }

    public function test_elemen_asing_dilaporkan_berurut(): void
    {
        $payload = [
            'resourceType' => 'Goal',
            'lifecycleStatus' => 'active',
            'startDateTime' => '2026-11-16',
            'description' => ['text' => 'HbA1c < 7%'],
        ];

        $this->assertSame(['startDateTime'], R4ElementGuard::unknown($payload));
    }

    public function test_payload_sah_tidak_dilaporkan(): void
    {
        $payload = [
            'resourceType' => 'Goal',
            'lifecycleStatus' => 'active',
            'startDate' => '2026-11-16',
            'description' => ['text' => 'HbA1c < 7%'],
            'subject' => ['reference' => 'Patient/1'],
        ];

        $this->assertSame([], R4ElementGuard::unknown($payload));
    }

    public function test_assert_menyebut_nama_elemen(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('startDateTime');

        R4ElementGuard::assert([
            'resourceType' => 'Goal',
            'startDateTime' => '2026-11-16',
        ]);
    }

    public function test_resource_di_luar_r4_tidak_diperiksa(): void
    {
        // ChargeItemResponse bukan resource R4; tidak ada dasar untuk menyebutnya salah.
        $this->assertSame([], R4ElementGuard::unknown([
            'resourceType' => 'ChargeItemResponse',
            'bebas' => 'apa saja',
        ]));
    }

    public function test_mode_ketat_mati_secara_bawaan(): void
    {
        $this->assertFalse(R4ElementGuard::strictEnabled());

        $builder = new class extends Builder {
            protected string $resourceType = 'Goal';

            public function bocor(): self
            {
                $this->data = ['resourceType' => 'Goal', 'startDateTime' => '2026-11-16'];

                return $this;
            }
        };

        $this->assertSame('2026-11-16', $builder->bocor()->build()['startDateTime']);
    }

    public function test_mode_ketat_menolak_payload_bocor(): void
    {
        putenv('SATUSEHAT_STRICT_R4=1');

        $builder = new class extends Builder {
            public function bocor(): self
            {
                $this->data = ['resourceType' => 'Goal', 'startDateTime' => '2026-11-16'];

                return $this;
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $builder->bocor()->build();
    }

    public function test_definisi_r4_tersedia_untuk_semua_resource_builder(): void
    {
        $definisi = json_decode(
            (string) file_get_contents(__DIR__.'/../../data/r4_elements.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $this->assertGreaterThanOrEqual(140, count($definisi));

        foreach (glob(__DIR__.'/../../src/Builder/PayloadBuilder*.php') as $berkas) {
            $resource = basename($berkas, '.php');
            $resource = substr($resource, strlen('PayloadBuilder'));
            $resource = ['ListResource' => 'List'][$resource] ?? $resource;
            if (in_array($resource, ['BillingStatus', 'ChargeItemResponse', 'GenomicStudy', 'ImagingManifest', 'ImagingSelection', 'PurificationDecision', 'ResourceGuide'], true)) {
                continue; // resource non-R4, sudah ditandai @deprecated
            }
            $this->assertArrayHasKey($resource, $definisi, "definisi R4 tidak memuat {$resource}");
        }
    }
}
