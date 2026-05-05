# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- VAT-EU (*Numer VAT UE*) validation and parsing — `Numerik::vatEu()->validate()`, `isValid()`, `parse()`, `tryParse()`
- `VatEu` value object with `getCountryCode()`, `getNip()`, `getFormatted()`
- IBAN (Polish IBAN = `PL` + NRB) validation and parsing — `Numerik::iban()->validate()`, `isValid()`, `parse()`, `tryParse()`
- `Iban` value object with `getFormatted()`, `getCountryCode()`, `getNrb()`, `getCheckDigits()`, `getSortCode()`, `getBankCode()`, `getAccountNumber()`
- NRB (*Numer Rachunku Bankowego*) validation and parsing — `Numerik::nrb()->validate()`, `isValid()`, `parse()`, `tryParse()`
- `Nrb` value object with `getFormatted()`, `getIban()`, `getFormattedIban()`, `getCheckDigits()`, `getSortCode()`, `getBankCode()`, `getAccountNumber()`
- Accepts raw 26-digit, spaced, and IBAN (`PL`-prefixed) input formats
- Polish (`pl`) translations for all documentation pages, with full content parity to the English version

### Fixed

- Quick-start code examples incorrectly showed `PESEL '00000000000'` returning `ValidationFailureReason::AllZeros`; it returns `InvalidMonth` (month encoding `00` is outside all valid century ranges). Corrected to `'92060512185'` → `InvalidChecksum`.
- KRS API URL was inconsistent between `algorithms.mdx` and `identifiers/krs.mdx`; both now point to `prs.ms.gov.pl/krs/openApi`
- KRS strict-mode `AllSameDigit` check was missing from the validation algorithm descriptions in `algorithms.mdx` and `identifiers/krs.mdx`
- NIP mod-11 checksum description implied a separate structural check for a result of `10`; clarified that it simply cannot match any digit 0–9 and always fails with `InvalidChecksum`

## [1.0.0] - 2026-04-18

### Added
- PESEL validation and parsing — `Numerik::pesel()->validate()`, `isValid()`, `parse()`, `tryParse()`
- REGON validation and parsing — `Numerik::regon()->validate()`, `isValid()`, `parse()`, `tryParse()`
- KRS validation and parsing — `Numerik::krs()->validate()`, `isValid()`, `parse()`, `tryParse()`
- NIP validation and parsing — `Numerik::nip()->validate()`, `isValid()`, `parse()`, `tryParse()`
- `Nip::getFormattedAlternative()` — alternative `NNN-NN-NN-NNN` display format
- `ValidatorInterface::isStrict()` — query strict mode on any identifier
- Documentation site using Astro Starlight with Flexoki theme
- Netlify deployment with custom domain (`numerik.slashlab.pl`)

---

[Unreleased]: https://github.com/sqrcz/numerik/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/slashlab/numerik/releases/tag/v1.0.0
