# Security Policy

## Supported Versions

| Version | Supported | PHP Requirement |
|---------|----------|----------------|
| 4.x     | ✅ Yes    | PHP 8.1+       |
| 3.x     | ✅ Yes    | PHP 7.4+       |
| 2.x     | ⚠️ Security fixes only | PHP 7.4+ |
| < 2.0   | ❌ No     | —              |

PHP 8.1+ is the recommended minimum. See [composer.json](composer.json) for exact `illuminate/*` version constraints.

## Reporting a Vulnerability

If you discover a security vulnerability, please report it responsibly.

### Private Reporting (Preferred)

For security issues that could affect production deployments or expose sensitive health data, please **do not open a public issue**. Instead:

1. Email the maintainer directly at **ivanwilliam.md@gmail.com**
2. Use the subject line: `[SECURITY] satusehat-integration`

### What to Include

Your report should contain:

- Description of the vulnerability
- Steps to reproduce the issue
- Affected version(s)
- Potential impact assessment
- Any suggested fixes (optional)

### What to Expect

- Acknowledgment of your report within **48 hours**
- A severity assessment within **7 days**
- A fix or resolution timeline based on severity
- Credit in the release notes (unless you request otherwise)

## Bug Reports (Non-Security)

For general bugs and issues, open a public [GitHub Issue](https://github.com/ivanwilliammd/satusehat-integration/issues) with:

- PHP version and Laravel version
- Steps to reproduce
- Expected vs actual behavior
- Code snippet or minimal reproduction case
- Full error message or stack trace

## Security Considerations

- Never commit API credentials, client secrets, or organization IDs to version control
- Store SATUSEHAT credentials in environment variables (`.env`), not in code
- The library handles OAuth2 tokens internally — do not expose or log raw bearer tokens
- FHIR resources may contain PHI (Protected Health Information) — ensure your application complies with applicable data protection regulations
