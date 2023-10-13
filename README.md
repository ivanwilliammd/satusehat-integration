# Build SATUSEHAT FHIR Object in Easy Way

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Tests](https://img.shields.io/github/actions/workflow/status/ivanwilliammd/satusehat-integration/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanwilliammd/satusehat-integration/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)

This unofficial SATUSEHAT FHIR PHP Library to help generate FHIR resources JSON and sent it via [SATUSEHAT API](https://satusehat.kemkes.go.id/platform).

## Fitur SATUSEHAT Fase 1 Rawat Jalan
Based on : SATUSEHAT Mandate PMK 24 tahun 2022 (Deadline December 2023) : 
- [x] OAuth2 (POST)
- [ ] Organization GET
- [x] Organization POST
- [x] Organization PUT
- [ ] Location GET
- [x] Location POST
- [x] Location PUT
- [ ] Patient GET by ID
- [ ] Patient GET by NIK
- [ ] Practitioner GET by ID
- [ ] Practitioner GET by NIK
- [ ] Encounter GET
- [x] Encounter POST
- [x] Encounter PUT
- [ ] Condition GET
- [ ] Condition POST
- [ ] Condition PUT
- [ ] KYC SATUSEHAT Platform

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

## Cara pemakaian

### Konfigurasi ClientID & ClientSecret dan Organization ID
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

## Dry Run

```php
/** 
 * Uji coba echo Token yang sesuai dan di DB akan tersimpan
 * Pastikan sudah mengisi konfigurasi di .env
*/

$client = new Satusehat\Integration\OAuth2Client;
echo $client->token();
```

### GET by ID

```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\OAuth2Client;

$client = new OAuth2Client;

$client->get('Organization', '{id}');
$client->get('Location', '{id}');
$client->get('Patient', '{id}');
$client->get('Practitioner', '{id}');
$client->get('Encounter', '{id}');
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

$client->get_by_nik('Patient', '{NIK Pasien}');
$client->get_by_nik('Practitioner', '{NIK Dokter}');
```

### POST
#### POST Encounter

```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\OAuth2Client;
use Satusehat\FHIR\Encounter;

$client = new OAuth2Client;

$data = new Encounter;
$data->setSubject('{SATUSEHAT ID Pasien}');

$client->post('Encounter', $data);

```

### PUT
#### PUT Encounter

```php
/** 
 * Proses GET / POST / PUT, tidak perlu lagi menggunakan deklarasi OAuth2Client->token()
*/
<?php

use Satusehat\Integration\OAuth2Client;
use Satusehat\FHIR\Encounter;

$client = new OAuth2Client;

$data = new Encounter;
$data->setSubject('{SATUSEHAT ID Pasien}');

$client->put('Encounter', '{id Encounter}', $data);

```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [dr. Ivan William Harsono, MTI](https://github.com/ivanwilliammd)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
