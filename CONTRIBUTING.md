# Contributing to numerik

## Development Setup

```bash
git clone https://github.com/sqrcz/numerik.git
cd numerik
composer install
```

## Running Checks

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

## Branch Naming

| Type     | Pattern              | Example                        |
| -------- | -------------------- | ------------------------------ |
| Feature  | `feat/<short-name>`  | `feat/passport-identifier`     |
| Fix      | `fix/<short-name>`   | `fix/pesel-century-calc`       |
| Chore    | `chore/<short-name>` | `chore/update-phpstan`         |

## Pull Request Process

1. Fork the repository and create your branch from `main`.
2. Add tests for any new behaviour — all tests must pass.
3. Run `composer check` before opening a PR.
4. Fill in the PR template completely.
5. PRs are merged with rebase — each commit lands on `main` individually, so keep your history clean and meaningful.

## Commit Messages

```
feat: add PassportIdentifier
fix: correct PESEL century detection for 2100s
docs: update REGON algorithm description
test: add NIP fixtures for invalid tax office codes
chore: update PHPStan to 2.0
refactor: extract checksum calculation to dedicated method
```

## Adding a New Identifier

Every new identifier requires all of the following — PRs missing any item will not be merged:

- [ ] `src/Identifiers/NewIdentifierIdentifier.php` — implements `ValidatorInterface` and `ParserInterface`
- [ ] `src/ValueObjects/NewIdentifier.php` — `final readonly class` with domain-specific getters
- [ ] `tests/Fixtures/NewIdentifierFixtures.php` — PHP fixture class with valid and invalid cases
- [ ] Unit tests for the identifier and value object
- [ ] Entry point added to `src/Numerik.php`
- [ ] CHANGELOG.md updated

## Code Style

PSR-12 with additional rules enforced by PHP CS Fixer.
Run `composer cs-fix` before committing — CI will reject style violations.

## Reporting Bugs

Use the [Bug Report](https://github.com/sqrcz/numerik/issues/new?template=bug_report.yml) template.

## Security

See [SECURITY.md](SECURITY.md).
