# Changelog

* Added: API KFA
* Updated : OAuth parameter changed from base_url to fhir_url

## 3.2.4 - 2024-12-02

### What's Changed

* updated construct in Medication & MedicationRequest by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/108
* updated addIdentifier in Medication & MedicationRequest by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/107

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/3.2.2...3.2.4

## 3.2.2 - 2024-12-01

### What's Changed

* 90 - updated situation where dose value and dose unit not declared (MedicationRequest) by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/104

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/3.2.0...3.2.2

## 3.2.0 - 2024-12-01

### What's Changed

* 89 medication model initial commit by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/102
* 90 medicationrequest by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/103

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/3.0.0...3.2.0

## 3.0.0 - 2024-11-29

### What's Changed

* 64 loinc terminology by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/65

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.8...3.0.0

## 2.9.8 - 2024-11-14

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.7...2.9.8

- @ivanwilliammd Revert mandatory requirement of Patient.address on https://github.com/ivanwilliammd/satusehat-integration/pull/60

## 2.9.7 - 2024-11-13

### What's Changed

* Add new physical location type ( Bed ) by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/63

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.6...2.9.7

## 2.9.6 - 2024-11-12

### What's Changed

* Disable required address in Patient by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/60
* Hotfix organization by @yudistirasd ft. @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/62

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.5...2.9.6

## 2.9.5 - 2024-11-04

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.3...2.9.5

Hotfix by @ivanwilliammd for kode wilayah Indonesia migration --> 2.9.x feat by @IrsyadProject)

## 2.9.3 - 2024-11-04

### What's Changed

* Update CSV data kode wilayah dari KEMENDAGRI Tahun 2023, update migration & seeder kode wilayah by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/59

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.2...2.9.3

## 2.9.2 - 2024-10-10

### What's Changed

* fix: bug migration kode wilayah indonesia by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/58

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.1...2.9.2

## 2.9.1 - 2024-10-10

### What's Changed

* fix: kode wilayah indonesia by @IrsyadProject in https://github.com/ivanwilliammd/satusehat-integration/pull/57

### New Contributors

* @IrsyadProject made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/57

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.9.0...2.9.1

## 2.9.0 - 2024-07-20

### What's Changed

* Bump dependabot/fetch-metadata from 2.1.0 to 2.2.0 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/54
* Fixed put function for for encounter, condition, location, observation

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.8.3...2.9.0

## 2.8.3 - 2024-07-02

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.8.2...2.8.3

- Full updated dependencies for Laravel 11 (Illuminate 11)

## 2.8.0 - 2024-07-02

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.7.0...2.8.0

- Add composer.json declaration to support php 8.2+ / 8.3+

## 2.7.0 - 2024-05-09

### What's Changed

* Bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/47
* Bump dependabot/fetch-metadata from 2.0.0 to 2.1.0 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/48
* Fix  seach patient by nik by @widialjatsiyah in https://github.com/ivanwilliammd/satusehat-integration/pull/50

### New Contributors

* @widialjatsiyah made their first contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/50

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.6.0...2.7.0

## 2.6.0 - 2024-04-10

### What's Changed

* Refactor Exception Handling with some fix by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/44
* 43 refactoring exception handlling by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/45
* fixed OAuthClient.php by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/46

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.5.1...2.6.0

## 2.5.1 - 2024-04-06

### What's Changed

* hotfix multitenancy feature and strictly typed OAuthClient.php by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/42

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.5.0...2.5.1

## 2.5.0 - 2024-04-06

### What's Changed

* Added: API KFA
* Updated : OAuth parameter changed from base_url to fhir_url
* KFA Integration by @yudistirasd in https://github.com/ivanwilliammd/satusehat-integration/pull/38
* 31 api kfa by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/39
* updated v2.5.0 by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/40

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.4.0...2.5.0

## 2.4.0 - 2024-04-03

### What's Changed

* Update: OAuth2Client, Patient, and Organization by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/36
* Added compatibility for Patient Post with identifier `nik-ibu`

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.3...2.4.0

## 2.3.3 - 2024-04-02

### What's Changed

* updated json function inside bundle by @ivanwilliammd in https://github.com/ivanwilliammd/satusehat-integration/pull/34

### New Contributors

* @ivanwilliammd made their contribution in https://github.com/ivanwilliammd/satusehat-integration/pull/34

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.2...2.3.3

## 2.3.2 - 2024-04-02

### What's Changed

* improvement: Condition throw exception error by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/29

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.1...2.3.2

## 2.3.1 - 2024-03-31

### What's Changed

* fix: Encounter & Condition bundle by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/27
* Linkage of urn:uuid between Encounter & Condition
* Updated wiki

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.3.0...2.3.1

## 2.3.0 - Initiation of bundle support - 2024-03-30

### What's Changed

* feat: Encounter & Condition bundle by @SyaefulKai in https://github.com/ivanwilliammd/satusehat-integration/pull/26

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.2.1...2.3.0

## v2.3.x :

- Initiation of bundle support
- Minor Bug fix

## v2.2.x :

- Change test to use PHPUnit 9 for support of 7.4, 8.1, 8.2, 8.3

## v2.1.x :

- Added Kode Wilayah Indonesia (KodWilId) class
- Minor default parameter of `ss_parameter_override` to false in satusehat config file
- Updated .env.example

## v2.0.x :

- Splitted terminology model
- Added new migration database, and seeder
- Expanded Practitioner GET Model
- Updated satusehat config file to support multitenancy with overloading in Controller using `http://github.com/mpociot/teamwork` package

Example of overloaded BaseController in Laravel 8+:

```php
<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Auth
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    //

    public function overrideEnvironment($ss_oauth2){

        $this->currentTeam= Auth::user()->currentTeam;
        $ss_oauth2->satusehat_env = $this->currentTeam->ss_environment;

        // Override construct parameter
        if($this->currentTeam){
            if($ss_oauth2->satusehat_env == 'PROD'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_PROD', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_PROD', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_prod_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_prod_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_prod_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'STG'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_stg_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_stg_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_stg_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'DEV'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_dev_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_dev_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_dev_organization_id;
            } else {
                return redirect()->route('admin.home')->withDanger('Anda belum menambahkan settingan environment SATUSEHAT pada Database.');
            }
        }

        return $ss_oauth2;
    }
}



























```
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

## 2.2.1 - Ensuring consistency of $this->json() - 2024-03-26

### What's Changed

* Bump dependabot/fetch-metadata from 1.6.0 to 2.0.0 by @dependabot in https://github.com/ivanwilliammd/satusehat-integration/pull/23

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.2.0...2.2.1

## 2.2.0 - Ensuring full compatibility from PHP 7.4, 8.1, 8.2, 8.3 - 2024-03-24

### What's Changed

* [WIP] Backward compatibility to PHP-7.4 by @YogiPristiawan in https://github.com/ivanwilliammd/satusehat-integration/pull/22

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.1.0...2.2.0

## 2.1.0 - Minor Adjustment + Kode Wilayah Indonesia Inclusion - 2024-03-22

**Full Changelog**: https://github.com/ivanwilliammd/satusehat-integration/compare/2.0.1...2.1.0

v2.1.x :

- Added Kode Wilayah Indonesia (KodWilId) class
- Minor default parameter of `ss_parameter_override` to false in satusehat config file
- Updated .env.example

## Major Terminology Update and Multitenancy Support with Overloadding - 2024-03-22

v2.0.x :

- Splitted terminology model
- Added new migration database, and seeder
- Expanded Practitioner GET Model
- Updated satusehat config file to support multitenancy with overloading in Controller using `http://github.com/mpociot/teamwork` package

Example of overloaded BaseController in Laravel 8+:

```php
<?php

namespace App\Http\Controllers\Satusehat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Auth
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    //

    public function overrideEnvironment($ss_oauth2){

        $this->currentTeam= Auth::user()->currentTeam;
        $ss_oauth2->satusehat_env = $this->currentTeam->ss_environment;

        // Override construct parameter
        if($this->currentTeam){
            if($ss_oauth2->satusehat_env == 'PROD'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_PROD', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_PROD', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_prod_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_prod_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_prod_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'STG'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_stg_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_stg_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_stg_organization_id;
            } elseif($ss_oauth2->satusehat_env == 'DEV'){
                $ss_oauth2->auth_url = getenv('SATUSEHAT_AUTH_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1');
                $ss_oauth2->base_url = getenv('SATUSEHAT_FHIR_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1');
                $ss_oauth2->client_id = $this->currentTeam->ss_dev_client_id;
                $ss_oauth2->client_secret = $this->currentTeam->ss_dev_client_secret;
                $ss_oauth2->organization_id = $this->currentTeam->ss_dev_organization_id;
            } else {
                return redirect()->route('admin.home')->withDanger('Anda belum menambahkan settingan environment SATUSEHAT pada Database.');
            }
        }

        return $ss_oauth2;
    }
}



























```
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
