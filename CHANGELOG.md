# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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

[1.0.0]: https://github.com/slashlab/numerik/releases/tag/v1.0.0
