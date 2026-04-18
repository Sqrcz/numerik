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

```bash
composer require slashlab/numerik-laravel
```

```php
// In a Form Request
public function rules(): array
{
    return [
        'pesel' => ['required', new PeselRule()],
        'nip'   => ['required', new NipRule()],
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
