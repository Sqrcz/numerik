---
title: NIP
description: Validate and parse Polish NIP tax identification numbers with Numerik.
---

NIP (*Numer Identyfikacji Podatkowej*) is Poland's 10-digit tax identification number, used by both individuals and legal entities.

## Usage

```php
use SlashLab\Numerik\Numerik;

// Boolean
Numerik::nip()->isValid('5260250274');   // true

// Rich result
$result = Numerik::nip()->validate('5260250274');
$result->isValid;   // true

// Parse to value object
$nip = Numerik::nip()->parse('5260250274');

// Null on failure instead of exception
$nip = Numerik::nip()->tryParse('bad-input'); // null
```

NIP accepts hyphens and spaces as separators in the input:

```php
Numerik::nip()->isValid('526-025-02-74');   // true
Numerik::nip()->isValid('526 025 02 74');   // true
```

## Value object API

`parse()` and `tryParse()` return a `SlashLab\Numerik\ValueObjects\Nip` instance.

### Core

| Method | Return type | Description |
|--------|-------------|-------------|
| `getRaw()` | `string` | The original input, untouched. |
| `getNormalized()` | `string` | 10 raw digits (hyphens and spaces removed). |
| `__toString()` | `string` | Same as `getNormalized()`. |

### Formatting & metadata

| Method | Return type | Description |
|--------|-------------|-------------|
| `getFormatted()` | `string` | Canonical `NNN-NNN-NN-NN` display form. |
| `getTaxOfficeCode()` | `string` | First 3 digits — indicates the issuing tax office. |

## Examples

```php
$nip = Numerik::nip()->parse('5260250274');

$nip->getRaw();            // '5260250274'
$nip->getNormalized();     // '5260250274'
$nip->getFormatted();      // '526-025-02-74'
$nip->getTaxOfficeCode();  // '526'

// With formatted input
$nip = Numerik::nip()->parse('526-025-02-74');
$nip->getRaw();            // '526-025-02-74'
$nip->getNormalized();     // '5260250274'
```

## Failure reasons

| Reason | Value | When |
|--------|-------|------|
| `InvalidLength` | `invalid_length` | Input is not exactly 10 digits after stripping separators. |
| `InvalidCharacters` | `invalid_characters` | Characters other than digits, hyphens, and spaces are present. |
| `InvalidFormat` | `invalid_format` | First 3 digits are `000` (no valid tax office has code 000). |
| `InvalidChecksum` | `invalid_checksum` | Checksum digit does not match. |
| `AllSameDigit` | `all_same_digit` | All digits are identical — strict mode only. |

## Validation algorithm

Weights: `6, 5, 7, 2, 3, 4, 5, 6, 7`

1. Strip hyphens and spaces. Assert exactly 10 digits.
2. Assert the first 3 digits are not `000`.
3. Multiply each of the first 9 digits by its weight, sum, take `mod 11`. The result must equal digit 10. If the modulo result is `10`, the number is structurally invalid — no valid NIP can have a checksum digit of `10`.

See [Algorithms](/guide/algorithms/) for the full reference.
