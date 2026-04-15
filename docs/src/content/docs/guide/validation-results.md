---
title: Validation Results
description: Understanding the ValidationResult and ValidationFailure classes returned by Numerik validators.
---

All four identifier classes (`PeselIdentifier`, `NipIdentifier`, `RegonIdentifier`, `KrsIdentifier`) expose the same two methods: `isValid()` and `validate()`. Neither ever throws an exception.

## ValidationResult

`validate()` returns a `SlashLab\Numerik\Result\ValidationResult` readonly class.

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `$isValid` | `bool` | `true` when validation passed. |
| `$failures` | `list<ValidationFailure>` | Empty array on success; one or more failures on failure. |

### Methods

| Method | Return type | Description |
|--------|-------------|-------------|
| `isFailed()` | `bool` | Inverse of `$isValid`. |
| `getFailures()` | `list<ValidationFailure>` | Returns the failures array. |
| `getFirstFailure()` | `ValidationFailure\|null` | First failure, or `null` if valid. |
| `hasFailureReason(ValidationFailureReason $reason)` | `bool` | `true` if any failure matches the given reason. |

### Examples

```php
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Enums\ValidationFailureReason;

// Passing result
$result = Numerik::pesel()->validate('92060512186');

$result->isValid;           // true
$result->isFailed();        // false
$result->failures;          // []
$result->getFirstFailure(); // null

// Failing result
$result = Numerik::nip()->validate('0000000000');

$result->isValid;           // false
$result->isFailed();        // true

// Inspect the first (and usually only) failure
$failure = $result->getFirstFailure();
$failure->reason;           // ValidationFailureReason::InvalidFormat
$failure->message;          // 'NIP tax office code cannot be 000.'

// Check for a specific reason
$result->hasFailureReason(ValidationFailureReason::InvalidChecksum); // false
$result->hasFailureReason(ValidationFailureReason::InvalidFormat);   // true
```

## ValidationFailure

Each item in `$failures` is a `SlashLab\Numerik\Result\ValidationFailure` readonly class.

### Properties

| Property | Type | Description |
|----------|------|-------------|
| `$reason` | `ValidationFailureReason` | Enum case identifying the failure category. |
| `$message` | `string` | Human-readable description in English. |

## ValidationFailureReason enum

`SlashLab\Numerik\Enums\ValidationFailureReason` is a backed string enum.

### Format failures

| Case | Value | Description |
|------|-------|-------------|
| `InvalidLength` | `invalid_length` | Input has the wrong number of digits. |
| `InvalidCharacters` | `invalid_characters` | Unexpected characters are present after stripping allowed separators. |
| `InvalidFormat` | `invalid_format` | Correct length and characters, but a structural rule is violated (e.g. NIP tax office code `000`). |

### Checksum failures

| Case | Value | Description |
|------|-------|-------------|
| `InvalidChecksum` | `invalid_checksum` | The computed checksum does not match the checksum digit. |

### Encoded-data failures

| Case | Value | Description |
|------|-------|-------------|
| `InvalidDate` | `invalid_date` | The date encoded inside the identifier is not a real calendar date. |
| `FutureDate` | `future_date` | The encoded birth date is in the future. |
| `InvalidMonth` | `invalid_month` | The month encoding does not correspond to any known century range. |

### Semantic failures

| Case | Value | Description |
|------|-------|-------------|
| `AllZeros` | `all_zeros` | All digits are zero — structurally plausible but semantically invalid. |
| `AllSameDigit` | `all_same_digit` | All digits are the same non-zero value. |

## Static factory methods

`ValidationResult` exposes three static constructors used internally and in tests:

```php
// Success
ValidationResult::pass();

// Failure with a list of failures
ValidationResult::fail([
    new ValidationFailure(ValidationFailureReason::InvalidChecksum, 'Checksum mismatch.'),
]);

// Failure with a single reason — shorthand
ValidationResult::failWithReason(
    ValidationFailureReason::InvalidLength,
    'Expected 11 digits, got 10.',
);
```
