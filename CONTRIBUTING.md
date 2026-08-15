# Contributing to satusehat-integration

Thank you for your interest in contributing!

## Requirements

- **PHP 8.1+**
- **Composer** for dependency management
- Familiarity with [FHIR R4](https://hl7.org/fhir/R4/) resources and the [SATUSEHAT API](https://satusehat.kemkes.go.id/platform/docs)

## Workflow

1. **Fork** the repository
2. **Clone** your fork:
   ```bash
   git clone https://github.com/YOUR_USERNAME/satusehat-integration.git
   cd satusehat-integration
   ```
3. **Create a new branch** for your change:
   ```bash
   git checkout -b feat/your-feature-name
   # or
   git checkout -b fix/your-bug-fix
   ```
4. **Install dependencies**:
   ```bash
   composer install
   ```
5. **Run tests** before and after making changes:
   ```bash
   composer test
   ```
6. **Make your changes** — see conventions below
7. **Commit and push**:
   ```bash
   git add .
   git commit -m "feat: add something useful"
   git push origin feat/your-feature-name
   ```
8. Open a **Pull Request** against the `main` branch

## Conventions

### DataType Classes

All DataType classes (located in `src/DataType/`) must:
- Extend the abstract `DataType` base class
- Implement a `toArray(): array` method that returns clean FHIR JSON
- The base `DataType::toArray()` handles recursive serialization of nested `DataType` instances automatically — prefer overriding `toArray()` only when custom filtering or formatting is needed
- Use typed constructor parameters and `protected` helper methods (`str()`, `int()`, `bool()`, `dt()`) from the base class for null-safe assignment
- Strip `null` and empty arrays from output (use `array_filter`)

Example of a correct DataType class:

```php
class HumanName extends DataType
{
    public ?string $use = null;
    public ?string $text = null;
    public ?string $family = null;
    public array $given = [];
    public array $prefix = [];
    public array $suffix = [];
    public ?Period $period = null;

    public function __construct(
        ?string $family = null,
        array $given = [],
        ?string $use = null,
        ?Period $period = null
    ) {
        $this->family = $this->str($family);
        $this->given = $given;
        $this->use = $this->str($use);
        $this->period = $period;
    }

    public function toArray(): array
    {
        $data = get_object_vars($this);
        if (isset($data['period']) && $data['period'] instanceof Period) {
            $data['period'] = $data['period']->toArray();
        }

        return array_filter($data, fn($v) => $v !== null && $v !== []);
    }
}
```

### PayloadBuilder Classes

New FHIR resources must be implemented as PayloadBuilder classes (in `src/Builder/`):
- Extend the abstract `Builder` base class
- Set `protected string $resourceType = 'ResourceName';`
- Provide a `build(): array` method (calls `parent::build()`)
- Use fluent method chaining (return `$this`)
- Accept DataType instances; call `->toArray()` when pushing to the data array
- Provide polymorphic setters for FHIR `[x]` elements where applicable

```php
class PayloadBuilderPatient extends Builder
{
    protected string $resourceType = 'Patient';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function addName(HumanName $name): self
    {
        $this->push('name', $name->toArray());
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}
```

### General

- Follow [PHP FIG PSR-12](https://www.php-fig.org/psr/psr-12/) coding style
- Use strict types: `declare(strict_types=1);` in every PHP file
- Do not add styling-only changes (commas, whitespace, tab alignment) — these will not be merged
- Significant contributors will be credited in the README

## Phase 3 Migration

This project is in Phase 3 of migration from `fhirvel-ss`. Resources being migrated include ClinicalImpression, CarePlan, Goal, NutritionOrder, and Composition. See [ROADMAP.md](ROADMAP.md) for the full plan.

## Related Documentation

- [SATUSEHAT Platform Docs](https://satusehat.kemkes.go.id/platform/docs)
- [HL7 FHIR R4 Specification](https://hl7.org/fhir/R4/)
- [CHANGELOG](CHANGELOG.md)
