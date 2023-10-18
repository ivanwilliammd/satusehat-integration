# Build SATUSEHAT FHIR Object in Easy Way

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Tests](https://img.shields.io/github/actions/workflow/status/ivanwilliammd/satusehat-integration/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanwilliammd/satusehat-integration/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)

This unofficial SATUSEHAT FHIR PHP Library to help generate FHIR resources JSON and sent it via [SATUSEHAT API](https://satusehat.kemkes.go.id/platform).

## How to contribute ?
As this is open source project, please kindly support this project by adding feature improvement or bug fixing. Styling fix (comma, delimiter, tab, spacing) will not be considered as contribution and will not considered for merge request. Significant contributor will be mentioned to [credits](#credits).<br>

1. Fork this repository
2. Clone your forked repository
3. Create new branch
4. Make changes
5. Commit and push
6. Create pull request

## Fitur SATUSEHAT Fase 1 Rawat Jalan
Based on : SATUSEHAT Mandate PMK 24 tahun 2022 (Deadline December 2023) : 

#### OAuth & KYC
- [x] OAuth2 (POST)
- [x] KYC SATUSEHAT Platform (Centang Biru SATUSEHAT Mobile)

#### Organization
- [x] Organization GET by ID
- [x] Organization POST
- [x] Organization PUT

#### Location
- [x] Location GET by ID
- [x] Location POST
- [x] Location PUT

#### Patient
- [x] Patient GET by NIK
- [x] Patient GET by ID

#### Practitioner
- [x] Practitioner GET by NIK
- [x] Practitioner GET by ID

#### Encounter
- [x] Encounter GET by ID
- [x] Encounter POST
- [x] Encounter PUT

#### Condition
- [x] ICD-10 Masterdata 202212 Version
- [x] Condition GET by ID
- [x] Condition POST
- [x] Condition PUT


## Installation

### Composer

```bash
composer require ivanwilliammd/satusehat-integration
```

### Publish Config

```bash
php artisan vendor:publish --provider="Satusehat\Integration\SatusehatIntegrationServiceProvider" --tag=config
```

### Publish Migration

```bash
php artisan vendor:publish --provider="Satusehat\Integration\SatusehatIntegrationServiceProvider" --tag=migrations
```

### Run Migration

```bash
php artisan migrate
```

### Publish ICD-10 Migration, Seeder, and CSV file (optional - to enable auto code lookup)
```bash
# Run to publish all assets regarding ICD-10
php artisan vendor:publish --provider="Satusehat\Integration\SatusehatIntegrationServiceProvider" --tag=icd10

# Run to migrate the ICD10 table
php artisan migrate

# Run to seed the data
php artisan db:seed --class=Icd10Seeder
```

## Cara pemakaian

### Konfigurasi ClientID & ClientSecret dan Organization ID
Isilah Organization ID, Client ID dan Client Secret yang diberikan oleh SATUSEHAT di file .env

```env
SATUSEHAT_ENV=xxxxxx (DEV/STG/PROD)

SATUSEHAT_AUTH_DEV=https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1
SATUSEHAT_FHIR_DEV=https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1

SATUSEHAT_AUTH_STG=https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1
SATUSEHAT_FHIR_STG=https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1

SATUSEHAT_AUTH_PROD=https://api-satusehat.kemkes.go.id/oauth2/v1
SATUSEHAT_FHIR_PROD=https://api-satusehat.kemkes.go.id/fhir-r4/v1

ORGID_DEV=xxxxxx
CLIENTID_DEV=xxxxxx
CLIENTSECRET_DEV=xxxxxx

ORGID_STG=xxxxxx
CLIENTID_STG=xxxxxx
CLIENTSECRET_STG=xxxxxx

ORGID_PROD=xxxxxx
CLIENTID_PROD=xxxxxx
CLIENTSECRET_PROD=xxxxxx
```

## Dry Run / Create Token

```php
/** 
 * Uji coba echo Token yang sesuai dan di DB akan tersimpan
 * Pastikan sudah mengisi konfigurasi di .env
*/

$client = new Satusehat\Integration\OAuth2Client;
echo $client->token();
```



## KYC (Verifikasi SATUSEHAT Centang Biru)
*Note : Wajib dilakukan pada konfigurasi .env ```SATUEHAT_ENV=PROD```

```php
/** 
 * Pastikan sudah mengisi konfigurasi di .env
 * Proses KYC tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\KYC;

$kyc = new KYC;

// Isi nama verifikator & NIK verifikator untuk mendapatkan link KYC
$json = $kyc->generateUrl('{nama_verifikator}', '{nik_verifikator}');
$kyc_link = json_decode($json, true);

/** 
 * Melakukan route redirect ke link KYC
 * saat ini hanya bisa dibuka pada tab baru / pop-up
 * tidak bisa melalui iframe
*/
return redirect($kyc_link['data']['url']);
```

### GET by ID

```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\OAuth2Client;

$client = new OAuth2Client;

// Organization
$client->get('Organization', '{id}');

// Location
$client->get('Location', '{id}');

// Patient
$client->get('Patient', '{id}');

// Practitioner
$client->get('Practitioner', '{id}');

// Encounter
$client->get('Encounter', '{id}');

// Condition
$client->get('Condition', '{id}');
```

### GET by NIK

```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\OAuth2Client;

$client = new OAuth2Client;

// Patient
$client->get_by_nik('Patient', '{NIK Pasien}');

// Practitioner
$client->get_by_nik('Practitioner', '{NIK Dokter}');
```

### Agnostic POST & PUT : using self build JSON object

```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\OAuth2Client;

$client = new OAuth2Client;
$body = ...... ; // JSON Object
$resource = ......; // Any FHIR Resource (Organization, Location, Patient, Practitioner, Encounter, Condition)

// General Format POST
$client->ss_post($resource, $body);

// General Format PUT
$id = ...... ; // SATUSEHAT response ID
$client->ss_put($resource, $id, $body);
```

### POST : Using FHIR Class Object
```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi kelas OAuth2Client
*/
<?php

use Satusehat\Integration\FHIR\Organization;
use Satusehat\Integration\FHIR\Location;
use Satusehat\Integration\FHIR\Encounter;
use Satusehat\Integration\FHIR\Condition;

// Organization
$organization = new Organization;
$organization->post();

// Location
$location = new Location;
$location->post();

// Encounter
$encounter = new Encounter;
$encounter->post();

// Condition
$condition = new Condition;
$condition->post();
```

### PUT : Using FHIR Class Object
```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi kelas OAuth2Client
*/
<?php

use Satusehat\Integration\FHIR\Organization;
use Satusehat\Integration\FHIR\Location;
use Satusehat\Integration\FHIR\Encounter;
use Satusehat\Integration\FHIR\Condition;

// Organization
$organization = new Organization;
$organization->put('{organization_id}');

// Location
$location = new Location;
$location->put('{location_id}');

// Encounter
$encounter = new Encounter;
$encounter->put('{encounter_id}');

// Condition
$condition = new Condition;
$condition->put('{condition_id}');
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits
1. [dr. Ivan William Harsono, MTI](https://github.com/ivanwilliammd)


## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
