<?php

namespace Satusehat\Integration\Tests\Terminology;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\Terminology\SatusehatNonStandard;

/**
 * Uji objek non-standar SATUSEHAT.
 *
 * Yang dijaga bukan "apakah URL-nya kelihatan benar" — itu tidak bisa diuji — melainkan bahwa nilai
 * di kode sama dengan berkas rujukan `data/satusehat/nonstandard.json`, dan berkas itu mencantumkan
 * sumbernya. Kalau seseorang mengganti URL karena ingatannya berbeda, uji ini gagal sampai berkas
 * rujukan ikut diubah — dan perubahan itu terlihat di diff, bukan tersembunyi di kode.
 */
class SatusehatNonStandardTest extends TestCase
{
    private function registry(): array
    {
        return json_decode(
            (string) file_get_contents(__DIR__.'/../../data/satusehat/nonstandard.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    public function test_identifier_system_sama_dengan_berkas_rujukan(): void
    {
        $reg = $this->registry();
        $this->assertSame($reg['identifier_system']['nik']['url'], SatusehatNonStandard::NIK);
        $this->assertSame($reg['identifier_system']['paspor']['url'], SatusehatNonStandard::PASPORT);
        $this->assertSame($reg['identifier_system']['kk']['url'], SatusehatNonStandard::KK);
        $this->assertSame($reg['identifier_system']['ihs']['url'], SatusehatNonStandard::IHS_NUMBER);
    }

    public function test_extension_sama_dengan_berkas_rujukan(): void
    {
        $reg = $this->registry();
        $this->assertSame($reg['extension']['administrative_code']['url'], SatusehatNonStandard::EXT_ADMINISTRATIVE_CODE);
        $this->assertSame($reg['extension']['birth_place']['url'], SatusehatNonStandard::EXT_BIRTH_PLACE);
        $this->assertSame($reg['extension']['citizenship_status']['url'], SatusehatNonStandard::EXT_CITIZENSHIP_STATUS);
        $this->assertSame($reg['extension']['patient_religion']['url'], SatusehatNonStandard::EXT_PATIENT_RELIGION);
        $this->assertSame($reg['extension']['patient_disability']['url'], SatusehatNonStandard::EXT_PATIENT_DISABILITY);
        $this->assertSame($reg['extension']['patient_citizenship']['url'], SatusehatNonStandard::EXT_PATIENT_CITIZENSHIP);
        $this->assertSame($reg['extension']['patient_birth_time']['url'], SatusehatNonStandard::EXT_PATIENT_BIRTH_TIME);
        $this->assertSame($reg['profile']['patient'], SatusehatNonStandard::PROFILE_PATIENT);
    }

    public function test_berkas_rujukan_mencantumkan_sumber(): void
    {
        $reg = $this->registry();
        $this->assertNotEmpty($reg['_sumber'] ?? null, 'berkas rujukan wajib menyebut sumber');
        $this->assertNotEmpty($reg['_diambil'] ?? null, 'berkas rujukan wajib menyebut tanggal pengambilan');
        foreach ($reg['identifier_system'] as $nama => $isi) {
            $this->assertStringStartsWith('https://fhir.kemkes.go.id/', $isi['url'], "system {$nama}");
        }
    }

    public function test_administrative_code_bersarang_lima_tingkat(): void
    {
        $ext = SatusehatNonStandard::administrativeCodeExtension([
            'province' => '32', 'city' => '3273', 'district' => '327301', 'village' => '3273010001',
        ]);

        $this->assertSame(SatusehatNonStandard::EXT_ADMINISTRATIVE_CODE, $ext['url']);
        $this->assertCount(4, $ext['extension']);
        $this->assertSame(['url' => 'province', 'valueString' => '32'], $ext['extension'][0]);
        $this->assertSame(['url' => 'village', 'valueString' => '3273010001'], $ext['extension'][3]);
    }

    public function test_administrative_code_menolak_tingkat_yang_tidak_ada(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SatusehatNonStandard::administrativeCodeExtension(['provinsi' => '32']);
    }

    public function test_extension_menempel_pada_address(): void
    {
        $alamat = new Address();
        $alamat->city = 'Bandung';
        $alamat->extension[] = SatusehatNonStandard::administrativeCodeExtension(['province' => '32', 'city' => '3273']);

        $json = $alamat->toArray();
        $this->assertSame('Bandung', $json['city']);
        $this->assertSame('province', $json['extension'][0]['extension'][0]['url']);
        $this->assertSame('32', $json['extension'][0]['extension'][0]['valueString']);
    }

    public function test_extension_codeable_membungkus_nilai(): void
    {
        $agama = SatusehatNonStandard::codeableExtension(SatusehatNonStandard::EXT_PATIENT_RELIGION, [
            'coding' => [[
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ReligiousAffiliation',
                'code' => '1001',
                'display' => 'Islam',
            ]],
        ]);

        $this->assertSame(SatusehatNonStandard::EXT_PATIENT_RELIGION, $agama['url']);
        $this->assertSame('1001', $agama['valueCodeableConcept']['coding'][0]['code']);
    }

    public function test_extension_codeable_menolak_canonical_lain(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SatusehatNonStandard::codeableExtension(SatusehatNonStandard::EXT_BIRTH_PLACE, ['text' => 'x']);
    }

    public function test_extension_codeable_menolak_nilai_kosong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        SatusehatNonStandard::codeableExtension(SatusehatNonStandard::EXT_PATIENT_DISABILITY, []);
    }

    public function test_birth_time_memakai_valuedatetime(): void
    {
        $ext = SatusehatNonStandard::birthTimeExtension('1990-01-01T03:15:00+07:00');

        $this->assertSame(SatusehatNonStandard::EXT_PATIENT_BIRTH_TIME, $ext['url']);
        $this->assertSame('1990-01-01T03:15:00+07:00', $ext['valueDateTime']);
    }
}
