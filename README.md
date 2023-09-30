# Build SATUSEHAT FHIR Object in Easy Way

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Tests](https://img.shields.io/github/actions/workflow/status/ivanwilliammd/satusehat-integration/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanwilliammd/satusehat-integration/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)

This unofficial SATUSEHAT FHIR PHP Library to help generate FHIR resources JSON and sent it via [SATUSEHAT API](https://satusehat.kemkes.go.id/platform).

## Checklist Phase 1 Outpatient
Based on : SATUSEHAT Mandate PMK 24 tahun 2022 (Deadline December 2023) : 
- [x] OAuth2
- [ ] Patient
- [ ] Practitioner
- [ ] Organization
- [ ] Location
- [ ] Encounter
- [ ] Condition

## Support us

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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

## Usage

## Usage

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

### Ujicoba ClientID & ClientSecret untuk mendapatkan Token

```php
/** 
 * Uji coba generate TokenID yang sesuai dan di DB akan tersimpan
 * Pastikan sudah mengisi konfigurasi di .env
*/

$client = new Satusehat\Integration\OAuth2;
echo $client->token();
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [dr. Ivan William Harsono, MTI](https://github.com/ivanwilliammd)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
