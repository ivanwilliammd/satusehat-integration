# Build SATUSEHAT FHIR Object in Easy Way

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Tests](https://img.shields.io/github/actions/workflow/status/ivanwilliammd/satusehat-integration/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanwilliammd/satusehat-integration/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)

SATUSEHAT FHIR PHP Library utilizing [DCarbone's PHP-FHIR library](https://github.com/dcarbone/php-fhir-generated) to generate FHIR resources.
The plus of this library is that it has a built-in validation for FHIR resources structure, ensuring that the resources send are structurally valid.


## Checklist Phase 1 Outpatient
Based on : SATUSEHAT Mandate PMK 24 tahun 2022 (Deadline December 2023) : 
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

You can install the package via composer:

```bash
composer require ivanwilliammd/satusehat-integration
```

## Usage

```php
$skeleton = new Ivanwilliammd\SatusehatIntegration();
echo $skeleton->echoPhrase('Hello, Ivanwilliammd!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ivanwilliammd](https://github.com/ivanwilliammd)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
