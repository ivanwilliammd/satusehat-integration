# Changelog

v2.0.x : 
- Splitted terminology model
- Added new migration database, and seeder
- Expanded Practitioner GET Model

v1.2.x :

- Backlog Compatilibity with Laravel 8+ (PHP 7.4) / Laravel 9 (PHP 8.0+) / Laravel 10 (PHP 8.1+)
- Bug fixing
- Splitted Encounter statusHistory
- Added functionality of Patient,
- Minor adjustmnent of Organization, Location, and OAuth2Client
- Minor bug fix

v1.1 :

- Standardize json() function to result encoded one with pretty print and no escape sequence
- Major functional fix of Encounter & Condition function class. Conversion to ATOM type datetime
- Added beta functionality of Observation
- Fixing inconsistency and function in Condition
- Updated timezone format

v1.0 :

- First beta version with PHP Class and consistency update of ICD 10-column migration
- Added faster batch import using csv seeder library

v0.15 :

- Last v0 series internally tested for creating OAuth 2
- Shipped basic method for GET by NIK function
- Shipped POST / PUT on FHIR object directly at Encounter, Condition, Organization, Location

## 1.2.1 - 2024-03-22

### What's Changed

* feat: setType organization by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/14
* #fix FHIR Location by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/19

### New Contributors

* @yudistirasd made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/14

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/1.2.0...1.2.1

## 1.2.0 - 2024-03-17

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.0 to 2.3.1 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/6
* fix: undefined variable by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/8
* feat: patient by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/9
* fix: organization by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/10
* fix: OAuth2Client by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/11
* split encounter addStatusHistory method by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/12
* Update Encounter, Condition and Observation by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/15

### New Contributors

* @SyaefulKai made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/8

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/1.1...1.2.0

## 1.2 - 2024-03-17

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.0 to 2.3.1 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/6
* fix: undefined variable by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/8
* feat: patient by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/9
* fix: organization by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/10
* fix: OAuth2Client by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/11
* split encounter addStatusHistory method by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/12
* Update Encounter, Condition and Observation by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/15

### New Contributors

* @SyaefulKai made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/8

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/1.1...1.2
