# Build SATUSEHAT FHIR Object in Easy Way

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)
[![Tests](https://img.shields.io/github/actions/workflow/status/ivanwilliammd/satusehat-integration/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ivanwilliammd/satusehat-integration/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ivanwilliammd/satusehat-integration.svg?style=flat-square)](https://packagist.org/packages/ivanwilliammd/satusehat-integration)

* This library is open-source and community-maintained, offered as-is without warranty or support from the repository [owner](https://github.com/ivanwilliammd). To enhance sustainability and address the growing demand for support and feature requests, a freemium/subscription-based package was implemented in 2025. For more information, contact: [ivan.harsono@ivanmd.id](mailto:ivan.harsono@ivanmd.id?subject=Subscription%20and%20Support%20for%20SATUSEHAT%20Integration&body=Salam%20sehat%2C%20dr.%20Ivan%0A%0ASaya%20berkebutuhan%20untuk%3A%20(sesuaikan%20dengan%20kebutuhan%20Anda)%0A1.%20Integrasi%20Resource%20%5Bsebutkan%5D%0A2.%20Support%20coding%2Fintegrasi%20database%0A3%20....%0A%0AAnda%20dapat%20mengontak%20saya%20kembali%20melalui%3A%0ANama%3A%20%0ANo.%20Whatsapp%3A%0A%0ATerimakasih%20banyak.)
* Feature comparison (perbandingan fitur Free VS Premium) --> click [here](FREEMIUM_COMPARISON.md)

## Introduction
- This unofficial SATUSEHAT FHIR PHP Library to help generate SATUSEHAT FHIR-ready JSON, using profile established by [SATUSEHAT Documentation](https://satusehat.kemkes.go.id/platform/docs).
- This repository is rapidly developing and need help. Please kindly comment in [Issue](https://github.com/ivanwilliammd/satusehat-integration/issues) section to contribute or Sponsor this project.
- Features supported --> see [Wiki](https://github.com/ivanwilliammd/satusehat-integration/wiki/Features)
- Error type from SATUSEHAT --> see [PUBLISHED - Dokumen Kamus Rule Number (Error Code)](https://docs.google.com/spreadsheets/d/1vnYFL2Ho1lICEgWmE2HFwkbEgiRvw1uaYBBW8NvwzjI/edit?gid=927500518#gid=927500518)

## SATUSEHAT dissemination summary
- Update (19/9/2024) : Medication is attached to MedicationRequest and MedicationDispense
- Update (21/11/2024):
    - SATUSEHAT implements multiple role access with restriction on each API service --> [Resource Access](https://drive.google.com/file/d/1bs8uU_nIuNqHohnRfTvFHx0o2qOgAYabAz0ptUC3w9s/view)
    - Data privacy security update, which will censored Patient and Practitioner name
    - Patient and Practitioner reference in ```Encounter.subject.display``` and ```Encounter.participant.individual``` must be same with Master Patient Index (Patient GET) and Master Nakes Index (Practitioner GET)

## Example Laravel 10 Project with SATUSEHAT Integration
See ```satusehat-integration``` library in action [here](https://github.com/ivanwilliammd/satusehat-laravel-example)

## Want to contribute?
- See how to contribute at this [page](CONTRIBUTING.md).<br>
- All contribution will be reviewed by [@ivanwilliammd](https://github.com/ivanwilliammd). Any invalid pull request will be commented, and decided directly whether will need further correction or directly closed as invalid.

## Quick Installation
See Quick Installation Instructions [here](https://github.com/ivanwilliammd/satusehat-integration/wiki/Installation)<br>
Feel your first time using this library at Onboarding page [here](https://github.com/ivanwilliammd/satusehat-integration/wiki/Onboarding)

## Features
See the feature Wiki page [here](https://github.com/ivanwilliammd/satusehat-integration/wiki/Features)

## Full usage guide
Fully documented usage guide could be found on the Usage Wiki section [here](https://github.com/ivanwilliammd/SATUSEHAT-integration/wiki/Usage)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

Active contributor (> 1 PR per quarter):
1. [Dr. dr. Ivan William Harsono, MTI](https://github.com/ivanwilliammd)
2. ... Looking for volunteer for active contribution ...

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
