# Numerik

[![Tests](https://github.com/sqrcz/numerik/actions/workflows/tests.yml/badge.svg)](https://github.com/sqrcz/numerik/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org)
[![Latest Version](https://img.shields.io/packagist/v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![PHP Version](https://img.shields.io/packagist/php-v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![License](https://img.shields.io/github/license/sqrcz/numerik.svg)](LICENSE)

> Modern PHP 8.3+ library for validating and parsing Polish identification
> numbers — PESEL, NIP, REGON, and KRS. Rich value objects, detailed error
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
$result = Numerik::pesel()->validate('92060512186');
$result->isValid;                          // true

$result = Numerik::pesel()->validate('00000000000');
$result->isFailed();                       // true
$result->getFirstFailure()->reason;        // ValidationFailureReason::AllZeros

// Parse to value object
$pesel = Numerik::pesel()->parse('92060512186');
$pesel->getBirthDate()->format('Y-m-d');  // '1992-06-05'
$pesel->getGender();                      // Gender::Female
```

## Documentation

Full documentation at **[numerik.slashlab.pl](https://numerik.slashlab.pl)**

## Laravel Integration

A dedicated Laravel package is available at [`slashlab/numerik-laravel`](https://github.com/Sqrcz/numerik-laravel) (requires PHP 8.3+, Laravel 11/12/13). The service provider is auto-discovered — no manual registration needed.

```bash
composer require slashlab/numerik-laravel
```

Use class-based rules or plain strings — both styles work:

```php
use SlashLab\NumerikLaravel\Rules\PeselRule;
use SlashLab\NumerikLaravel\Rules\NipRule;
use SlashLab\NumerikLaravel\Rules\RegonRule;
use SlashLab\NumerikLaravel\Rules\KrsRule;

// Class-based (supports options)
public function rules(): array
{
    return [
        'pesel' => ['required', new PeselRule()],           // strict mode on by default
        'pesel' => ['required', new PeselRule(strict: false)], // disable strict checks
        'nip'   => ['required', new NipRule()],
        'regon' => ['required', new RegonRule()],
        'krs'   => ['required', new KrsRule()],
    ];
}

// String-based
public function rules(): array
{
    return [
        'pesel' => ['required', 'pesel'],
        'nip'   => ['required', 'nip'],
        'regon' => ['required', 'regon'],
        'krs'   => ['required', 'krs'],
    ];
}
```

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

MIT — see [LICENSE](LICENSE).

---

If this saved you time → [☕ Buy me a coffee](https://buymeacoffee.com/sqrcz)
