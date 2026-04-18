# Contributing to Numerik

Thank you for your interest in contributing!

## Development Setup

```bash
git clone https://github.com/sqrcz/numerik.git
cd numerik
composer install
```

## Running Checks Locally

```bash
composer test        # PHPUnit test suite
composer stan        # PHPStan static analysis (level 10)
composer cs-fix      # Fix code style automatically
composer cs-check    # Check code style without fixing
composer mutation    # Infection mutation testing
composer check       # Run cs-check + stan + test in sequence
```

## Workflow

- All changes go through a pull request — no direct commits to `main`
- Keep PRs focused — one feature or fix per PR
- Use [Conventional Commits](https://www.conventionalcommits.org) for commit messages

## Commit Message Format

- `feat`: add personal ID validation
- `fix`: correct PESEL century detection for 2100s
- `docs`: update REGON algorithm description
- `test`: add NIP fixtures for invalid tax office codes
- `chore`: update PHPStan to 2.0
- `refactor`: extract checksum calculation to dedicated method

## Adding a New Identifier

Every new identifier requires all of the following — PRs missing any item will not be merged:

- [ ] `src/ValueObjects/NewIdentifier.php` — readonly value object
- [ ] `src/Validators/NewIdentifierValidator.php`
- [ ] `src/Parsers/NewIdentifierParser.php`
- [ ] `tests/Fixtures/new_identifier_valid.txt`
- [ ] `tests/Fixtures/new_identifier_invalid.txt`
- [ ] Unit tests for validator, parser, and value object
- [ ] Entry point added to `src/Numerik.php`
- [ ] CHANGELOG.md updated

## Code Style

PSR-12 with additional rules enforced by PHP CS Fixer.
Run `composer cs-fix` before committing — CI will reject style violations.

## Reporting Bugs

Use the [Bug Report](https://github.com/sqrcz/numerik/issues/new?template=bug_report.yml) template.

## Security

See [SECURITY.md](SECURITY.md).
