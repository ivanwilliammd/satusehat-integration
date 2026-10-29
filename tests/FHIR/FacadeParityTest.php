<?php

namespace Satusehat\Integration\Tests\FHIR;

use PHPUnit\Framework\TestCase;
use Satusehat\Integration\Builder\PayloadBuilderAllergyIntolerance;
use Satusehat\Integration\Builder\PayloadBuilderDevice;
use Satusehat\Integration\Builder\PayloadBuilderDiagnosticReport;
use Satusehat\Integration\Builder\PayloadBuilderLocation;
use Satusehat\Integration\Builder\PayloadBuilderOrganization;
use Satusehat\Integration\Builder\PayloadBuilderProcedure;
use Satusehat\Integration\DataType\Address;
use Satusehat\Integration\DataType\Annotation;
use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Coding;
use Satusehat\Integration\DataType\ContactPoint;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;
use Satusehat\Integration\FHIR\AllergyIntolerance as FacadeAllergyIntolerance;
use Satusehat\Integration\FHIR\Device as FacadeDevice;
use Satusehat\Integration\FHIR\DiagnosticReport as FacadeDiagnosticReport;
use Satusehat\Integration\FHIR\Location as FacadeLocation;
use Satusehat\Integration\FHIR\Organization as FacadeOrganization;
use Satusehat\Integration\FHIR\Procedure as FacadeProcedure;

/**
 * Uji paritas: facade lama (Satusehat\Integration\FHIR\*) dan PayloadBuilder native
 * harus menghasilkan payload FHIR yang SAMA untuk masukan yang sama.
 *
 * Tujuannya bukan menguji facade itu sendiri, melainkan memastikan pemakaian lama
 * tidak berubah arti setelah pemakaian baru (PayloadBuilder) tersedia.
 */
class FacadeParityTest extends TestCase
{
    private const DOC = __DIR__.'/../../docs/FACADE_PARITY.md';

    /** Bandingkan payload facade (string JSON) dengan payload builder (array). */
    private function assertParitySame(string $label, array $facade, array $builder): void
    {
        $facade = $this->canonical($facade);
        $builder = $this->canonical($builder);

        $this->assertSame([], $this->diff($facade, $builder), $label.' — payload facade dan builder berbeda');
    }

    private function canonical(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }
        foreach ($value as $k => $v) {
            $value[$k] = $this->canonical($v);
        }
        ksort($value);

        return $value;
    }

    /** @return list<string> daftar path yang berbeda, dalam bentuk yang bisa dibaca */
    private function diff(array $a, array $b, string $path = ''): array
    {
        $out = [];
        foreach (array_unique(array_merge(array_keys($a), array_keys($b))) as $key) {
            $p = $path.'/'.$key;
            $x = $a[$key] ?? '<tidak ada>';
            $y = $b[$key] ?? '<tidak ada>';
            if (is_array($x) && is_array($y)) {
                $out = array_merge($out, $this->diff($x, $y, $p));
            } elseif ($x !== $y) {
                $out[] = $p.': facade='.json_encode($x).' builder='.json_encode($y);
            }
        }

        return $out;
    }

    public function test_allergy_intolerance_facade_matches_native_builder(): void
    {
        $facade = new FacadeAllergyIntolerance();
        $facade->addIdentifier('http://sys-ids.kemkes.go.id/allergy/1000004', '98457729');
        $facade->setClinicalStatus('active');
        $facade->setVerificationStatus('confirmed');
        $facade->setType('allergy');
        $facade->addCategory('food');
        $facade->setCode('89811004', 'Gluten (substance)', 'Alergi gluten');
        $facade->setPatient('Patient/100000030009', 'Budi Santoso');
        $facade->setEncounter('Encounter/abc', 'Kunjungan');
        $facade->setRecordedDate('2022-06-14T15:37:31+00:00');
        $facade->setRecorder('Practitioner/N10000001');
        $facade->addNote('catatan');

        $builder = new PayloadBuilderAllergyIntolerance();
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/allergy/1000004', '98457729'));
        $builder->setClinicalStatus('active');
        $builder->setVerificationStatus('confirmed');
        $builder->setType('allergy');
        $builder->addCategory('food');
        $code = (new CodeableConcept())->addCoding(new Coding('http://snomed.info/sct', '89811004', 'Gluten (substance)'));
        $code->text = 'Alergi gluten';
        $builder->setCode($code);
        $builder->setPatient(new Reference('Patient/100000030009', 'Budi Santoso'));
        $builder->setEncounter(new Reference('Encounter/abc', 'Kunjungan'));
        $builder->setRecordedDate('2022-06-14T15:37:31+00:00');
        $builder->setRecorder(new Reference('Practitioner/N10000001'));
        $builder->addNote('catatan');

        $this->assertParitySame('AllergyIntolerance', json_decode($facade->json(), true), $builder->json());
    }

    public function test_device_facade_matches_native_builder(): void
    {
        $facade = new FacadeDevice();
        $facade->addIdentifier('http://sys-ids.kemkes.go.id/device/1000004', 'DEV-1');
        $facade->setStatus('active');
        $facade->setManufacturer('Philips');
        $facade->addDeviceName('Ventilator');
        $facade->setType('30349004', 'Infusion pump');
        $facade->setPatient('Patient/P1', 'Budi');
        $facade->setOwner('Organization/O1', 'RS Sehat');
        $facade->setLocation('Location/L1', 'ICU');
        $facade->setSerialNumber('SN-1');
        $facade->addNote('catatan');

        $builder = new PayloadBuilderDevice();
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/device/1000004', 'DEV-1'));
        $builder->setStatus('active');
        $builder->setManufacturer('Philips');
        $builder->addDeviceName('Ventilator');
        $builder->setType((new CodeableConcept())->addCoding(new Coding('http://snomed.info/sct', '30349004', 'Infusion pump')));
        $builder->setPatient(new Reference('Patient/P1', 'Budi'));
        $builder->setOwner(new Reference('Organization/O1', 'RS Sehat'));
        $builder->setLocation(new Reference('Location/L1', 'ICU'));
        $builder->setSerialNumber('SN-1');
        $builder->addNote('catatan');

        $this->assertParitySame('Device', json_decode($facade->json(), true), $builder->json());
    }

    public function test_diagnostic_report_facade_matches_native_builder(): void
    {
        $facade = new FacadeDiagnosticReport();
        $facade->addIdentifier('http://sys-ids.kemkes.go.id/diagnostic/1000004', 'DIAG-1');
        $facade->setStatus('final');
        $facade->addCategory('http://terminology.hl7.org/CodeSystem/v2-0074', 'LAB', 'Laboratory');
        $facade->setCode('http://loinc.org', '58410-2', 'CBC panel', 'Darah lengkap');
        $facade->setSubject('Patient/P1', 'Budi');
        $facade->setEncounter('Encounter/E1');
        $facade->setEffectiveDateTime('2022-06-14T15:37:31+00:00');
        $facade->setIssued('2022-06-14T15:40:00+00:00');
        $facade->addPerformer('Practitioner/PR1', 'dr. A');
        $facade->addResult('Observation/OBS1');
        $facade->addSpecimen('Specimen/SP1');
        $facade->setConclusion('Normal');

        $builder = new PayloadBuilderDiagnosticReport();
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/diagnostic/1000004', 'DIAG-1'));
        $builder->setStatus('final');
        $builder->addCategory((new CodeableConcept())->addCoding(new Coding('http://terminology.hl7.org/CodeSystem/v2-0074', 'LAB', 'Laboratory')));
        $code = (new CodeableConcept())->addCoding(new Coding('http://loinc.org', '58410-2', 'CBC panel'));
        $code->text = 'Darah lengkap';
        $builder->setCode($code);
        $builder->setSubject(new Reference('Patient/P1', 'Budi'));
        $builder->setEncounter(new Reference('Encounter/E1'));
        $builder->setEffectiveDateTime('2022-06-14T15:37:31+00:00');
        $builder->setIssued('2022-06-14T15:40:00+00:00');
        $builder->addPerformer(new Reference('Practitioner/PR1', 'dr. A'));
        $builder->addResult(new Reference('Observation/OBS1'));
        $builder->addSpecimen(new Reference('Specimen/SP1'));
        $builder->setConclusion('Normal');

        $this->assertParitySame('DiagnosticReport', json_decode($facade->json(), true), $builder->json());
    }

    /**
     * Procedure: facade lama dan builder native harus menghasilkan payload yang sama.
     * `setLocation()` facade sengaja TIDAK ikut diuji — builder belum punya padanannya dan bentuk
     * facade-nya (Reference datar) tidak sesuai tapak FHIR `Procedure.location` 0..* BackboneElement;
     * selisih itu dicatat di docs/FACADE_PARITY.md sebagai cacat terbuka, bukan dinormalkan di sini.
     */
    public function test_procedure_facade_matches_native_builder(): void
    {
        $facade = new FacadeProcedure();
        $facade->addIdentifier('http://sys-ids.kemkes.go.id/procedure/1000004', 'PROC-1');
        $facade->setStatus('completed');
        $facade->setCategory('387713003', 'Surgical procedure', 'http://snomed.info/sct', 'Tindakan bedah');
        $facade->setCode('86.22', 'Excisional biopsy of breast');
        $facade->setSubject('Patient/P1', 'Budi Santoso');
        $facade->setEncounter('enc-1', 'Kunjungan');
        $facade->addPerformer('Practitioner/N1', 'dr. Ani');
        $facade->setPerformedPeriod('2026-01-01T08:00:00+07:00', '2026-01-01T09:00:00+07:00');
        $facade->addReasonCode('C50', 'Neoplasma mammae');
        $facade->addBodySite('80248007', 'Left breast');
        $facade->addNote('catatan tindakan');
        $facade->addUsedCode('9100020000002', 'Alat bedah');
        $facade->addBasedOn('ServiceRequest/sr-1');

        $builder = new PayloadBuilderProcedure();
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/procedure/1000004', 'PROC-1'));
        $builder->setStatus('completed');
        $builder->setCategory((new CodeableConcept())
            ->addCoding(new Coding('http://snomed.info/sct', '387713003', 'Surgical procedure'))
            ->setText('Tindakan bedah'));
        $builder->setCode((new CodeableConcept())
            ->addCoding(new Coding('http://hl7.org/fhir/sid/icd-9-cm', '86.22', 'Excisional biopsy of breast')));
        $builder->setSubject(new Reference('Patient/P1', 'Budi Santoso'));
        $builder->setEncounter(new Reference('Encounter/enc-1', 'Kunjungan'));
        $builder->addPerformer(new Reference('Practitioner/N1', 'dr. Ani'));
        $builder->setPerformedPeriod(new Period('2026-01-01T08:00:00+07:00', '2026-01-01T09:00:00+07:00'));
        $builder->addReasonCode((new CodeableConcept())
            ->addCoding(new Coding('http://hl7.org/fhir/sid/icd-10', 'C50', 'Neoplasma mammae')));
        $builder->addBodySite((new CodeableConcept())
            ->addCoding(new Coding('http://snomed.info/sct', '80248007', 'Left breast')));
        $builder->addNote('catatan tindakan');
        $builder->addUsedCode((new CodeableConcept())
            ->addCoding(new Coding('http://sys-ids.kemkes.go.id/kfa', '9100020000002', 'Alat bedah')));
        $builder->addBasedOn(new Reference('ServiceRequest/sr-1'));

        $this->assertParitySame('Procedure', json_decode($facade->json(), true), $builder->json());
    }

    /**
     * Shim `build()` pada facade harus menunjuk builder yang resourceType-nya cocok, dan daftar
     * yang diuji di sini harus SAMA dengan yang tertulis di docs/FACADE_PARITY.md — supaya
     * dokumen tidak bisa basi tanpa uji gagal.
     */
    public function test_shim_list_matches_documented_parity_matrix(): void
    {
        $this->assertFileExists(self::DOC, 'docs/FACADE_PARITY.md hilang');

        preg_match_all('/^\- shim: `([^`]+)`/m', (string) file_get_contents(self::DOC), $m);
        $terdokumen = $m[1];

        $shipless = [];
        foreach (glob(__DIR__.'/../../src/FHIR/*.php') as $berkas) {
            $kelas = 'Satusehat\\Integration\\FHIR\\'.basename($berkas, '.php');
            if (method_exists($kelas, 'build')) {
                $builder = $kelas::build();
                $payload = $builder->json();
                $this->assertSame(
                    substr(basename($berkas), 0, -4),
                    $payload['resourceType'] ?? null,
                    basename($berkas).'::build() mengembalikan builder dengan resourceType berbeda'
                );
                $shipless[] = basename($berkas, '.php');
            }
        }

        sort($shipless);
        sort($terdokumen);
        $this->assertSame(
            $terdokumen,
            $shipless,
            'Daftar facade ber-shim() berbeda dari docs/FACADE_PARITY.md — perbarui dokumennya'
        );
        $this->assertNotEmpty($shipless);
    }

    /** addNote() menerima teks biasa pada SEMUA builder yang memilikinya. */
    public function test_add_note_accepts_plain_text_in_every_builder(): void
    {
        $diuji = 0;
        foreach (glob(__DIR__.'/../../src/Builder/PayloadBuilder*.php') as $berkas) {
            $kelas = 'Satusehat\\Integration\\Builder\\'.basename($berkas, '.php');
            if (! class_exists($kelas) || ! method_exists($kelas, 'addNote')) {
                continue;
            }
            $builder = new $kelas();
            $builder->addNote('catatan teks biasa');
            $payload = $builder->json();
            $this->assertSame(
                'catatan teks biasa',
                $payload['note'][0]['text'] ?? null,
                basename($berkas).' — addNote(string) tidak menghasilkan note[0].text'
            );
            $diuji++;
        }
        $this->assertGreaterThanOrEqual(13, $diuji, 'jumlah builder ber-addNote() menyusut tak terduga');

        // Annotation tetap diterima, dengan author dan text di tempatnya masing-masing.
        $device = new PayloadBuilderDevice();
        $device->addNote(new Annotation('Practitioner/PR1', 'catatan annotation'));
        $note = $device->json()['note'][0];
        $this->assertSame('catatan annotation', $note['text']);
        $this->assertSame('Practitioner/PR1', $note['author']);
    }

    /**
     * Empat facade FHIR memakai basis `Element` yang dulu tidak ada di repo, sehingga kelasnya
     * gagal di-instansiasi ("Class ... Element not found"). Uji ini menjaga basisnya tetap ada.
     */
    public function test_element_facades_can_be_instantiated(): void
    {
        foreach (['Immunization', 'MedicationAdministration', 'MedicationDispense', 'Specimen'] as $nama) {
            $kelas = 'Satusehat\\Integration\\FHIR\\'.$nama;
            $this->assertTrue(class_exists($kelas), $nama.' tidak bisa dimuat');
            $objek = new $kelas();
            $this->assertInstanceOf(\Satusehat\Integration\FHIR\Element::class, $objek);
            $this->assertSame($nama, $objek->json()['resourceType'] ?? null, $nama.' — resourceType salah');
        }
    }

    public function test_organization_facade_matches_native_builder(): void
    {
        $facade = new FacadeOrganization();
        $facade->organization_id = '1000004';
        $facade->addIdentifier('ORG-1');
        $facade->setName('RS Sehat');
        $facade->setType('prov');
        $facade->addPhone('021-1234567');
        $facade->addEmail('admin@rssehat.id');
        $facade->addUrl('https://rssehat.id');
        $facade->addAddress('Jl. Sehat 1', '12345', 'Jakarta', '31.71.01.1001');
        $facade->setPartOf('2000002');

        $builder = new PayloadBuilderOrganization();
        $builder->setActive(true);
        $builder->addIdentifier(new Identifier('http://sys-ids.kemkes.go.id/organization/1000004', 'ORG-1', 'official'));
        $builder->setName('RS Sehat');
        $builder->setType((new CodeableConcept())->addCoding(
            new Coding('http://terminology.hl7.org/CodeSystem/organization-type', 'prov', 'Healthcare Provider')
        ));
        $builder->addTelecom(new ContactPoint('phone', '021-1234567', 'work'));
        $builder->addTelecom(new ContactPoint('email', 'admin@rssehat.id', 'work'));
        $builder->addTelecom(new ContactPoint('url', 'https://rssehat.id', 'work'));
        $address = new Address();
        $address->use = 'work';
        $address->type = 'both';
        $address->line = ['Jl. Sehat 1'];
        $address->city = 'Jakarta';
        $address->postalCode = '12345';
        $address->country = 'ID';
        // Facade lama menyisipkan extension administrativeCode SATUSEHAT sendiri; builder tidak
        // menambahkannya otomatis, jadi sisi builder menyusunnya eksplisit (bentuk yang sama).
        $address->extension = [[
            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
            'extension' => [
                ['url' => 'province', 'valueCode' => '31'],
                ['url' => 'city', 'valueCode' => '3171'],
                ['url' => 'district', 'valueCode' => '317101'],
                ['url' => 'village', 'valueCode' => '3171011001'],
            ],
        ]];
        $builder->addAddress($address);
        $builder->setPartOf(new Reference('Organization/2000002'));

        $this->assertParitySame('Organization', json_decode($facade->json(), true), $builder->json());
    }

    /**
     * Tapak `type` pada Organization/Location adalah 0..* di FHIR R4, jadi payload WAJIB berupa
     * daftar CodeableConcept. Facade lama sudah benar; builder native sempat menulis satu objek.
     * Uji ini menjaga bentuk daftarnya, terpisah dari uji paritas, supaya cacatnya tidak bisa
     * kembali tanpa terlihat.
     */
    public function test_list_cardinality_fields_are_arrays(): void
    {
        $kasus = [
            [PayloadBuilderOrganization::class, 'http://terminology.hl7.org/CodeSystem/organization-type', 'prov'],
            [PayloadBuilderLocation::class, 'http://terminology.hl7.org/CodeSystem/location-type', 'ICU'],
        ];

        foreach ($kasus as [$kelas, $system, $kode]) {
            $payload = (new $kelas())
                ->setType((new CodeableConcept())->addCoding(new Coding($system, $kode, null)))
                ->json();

            $this->assertIsArray($payload['type'] ?? null, basename(str_replace('\\', '/', $kelas)).' — type harus daftar (0..*)');
            $this->assertSame($kode, $payload['type'][0]['coding'][0]['code'] ?? null, $kelas.' — type[0] tidak memuat kode');
        }
    }
}
