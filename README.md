# Numerik

[![Tests](https://github.com/sqrcz/numerik/actions/workflows/tests.yml/badge.svg)](https://github.com/sqrcz/numerik/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org)
[![Latest Version](https://img.shields.io/packagist/v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![PHP Version](https://img.shields.io/packagist/php-v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![License](https://img.shields.io/github/license/sqrcz/numerik.svg)](LICENSE)
[![CodeRabbit](https://img.shields.io/coderabbit/prs/github/sqrcz/numerik)](https://coderabbit.ai)

> Modern PHP 8.3+ library for validating and parsing Polish identification
> numbers — PESEL, NIP, REGON, KRS, NRB, VAT-EU, IBAN, ID Card, and Passport. Rich value objects, detailed error
> reasons, zero production dependencies.

## Installation

```bash
composer require slashlab/numerik
```

## Quick Start

```php
use SlashLab\Numerik\Numerik;

// Simple boolean check
Numerik::pesel()->isValid('92060512186');  // true
Numerik::nip()->isValid('5260250274');     // true

// Rich validation result with failure reasons
$result = Numerik::pesel()->validate('92060512185');  // wrong checksum digit
$result->isFailed();                       // true
$result->getFirstFailure()->reason;        // ValidationFailureReason::InvalidChecksum

// Parse to value object
$pesel = Numerik::pesel()->parse('92060512186');
$pesel->getBirthDate()->format('Y-m-d');  // '1992-06-05'
$pesel->getGender();                      // Gender::Female

// Try-parse — returns null on failure instead of throwing
$nip = Numerik::nip()->tryParse('5260250274');
$nip?->getFormatted();                     // '526-025-02-74'
```

## Strict Mode

All identifiers accept an optional `strict` parameter (default `true`), and it never affects how input is normalized — normalization (stripping spaces, and dashes where the format allows them) is the same in both modes. What `strict` gates is extra semantic plausibility checks: rejecting all-same-digit numbers (PESEL, NIP, KRS, VAT-EU) and future birth dates (PESEL). ID Card, Passport, REGON, NRB, and IBAN have no additional strict-mode checks.

```php
Numerik::nip(strict: false)->isValid('1111111111');  // true
Numerik::nip(strict: true)->isValid('1111111111');   // false — all-same-digit
```

## Documentation

Full documentation at **[numerik.slashlab.pl](https://numerik.slashlab.pl)**

## JavaScript / TypeScript Port

A TypeScript port is available at [`@slashlab/numerik-js`](https://github.com/sqrcz/numerik-js). It mirrors this library's API — same identifiers, same value objects, same strict mode — with Zod integration included.

```bash
npm install @slashlab/numerik-js
```

## Laravel Integration

A dedicated Laravel package is available at [`slashlab/numerik-laravel`](https://github.com/sqrcz/numerik-laravel) (PHP 8.3+, Laravel 12/13). It provides class-based and string-based validation rules with per-failure-reason error messages, English and Polish translations, and extended `PeselRule` constraints (`gender`, `bornBefore`, `bornAfter`).

```bash
composer require slashlab/numerik-laravel
```

See the [numerik-laravel README](https://github.com/sqrcz/numerik-laravel#readme) for full usage.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## Security

See [SECURITY.md](SECURITY.md).

## License

MIT — see [LICENSE](LICENSE).

---

If this saved you time → [☕ Buy me a coffee](https://buymeacoffee.com/sqrcz)
