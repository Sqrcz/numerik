[🇬🇧 English](README.md) | [🇵🇱 Polski](README.pl.md)

# Numerik

[![Tests](https://github.com/sqrcz/numerik/actions/workflows/tests.yml/badge.svg)](https://github.com/sqrcz/numerik/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org)
[![Latest Version](https://img.shields.io/packagist/v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![PHP Version](https://img.shields.io/packagist/php-v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![License](https://img.shields.io/github/license/sqrcz/numerik.svg)](LICENSE)
[![CodeRabbit](https://img.shields.io/coderabbit/prs/github/sqrcz/numerik)](https://coderabbit.ai)

> Nowoczesna biblioteka PHP 8.3+ do walidacji i parsowania polskich numerów
> identyfikacyjnych — PESEL, NIP, REGON, KRS, NRB, VAT-UE, IBAN, dowód osobisty i paszport. Bogate obiekty wartości, szczegółowe przyczyny błędów, zero zależności produkcyjnych.

## Instalacja

```bash
composer require slashlab/numerik
```

## Szybki start

```php
use SlashLab\Numerik\Numerik;

// Prosty wynik boolowski
Numerik::pesel()->isValid('92060512186');  // true
Numerik::nip()->isValid('5260250274');     // true

// Szczegółowy wynik walidacji wraz z przyczyną błędu
$result = Numerik::pesel()->validate('92060512185');  // błędna cyfra kontrolna
$result->isFailed();                       // true
$result->getFirstFailure()->reason;        // ValidationFailureReason::InvalidChecksum

// Parsowanie do obiektu wartości
$pesel = Numerik::pesel()->parse('92060512186');
$pesel->getBirthDate()->format('Y-m-d');  // '1992-06-05'
$pesel->getGender();                      // Gender::Female

// Parsowanie bez wyjątków — zwraca null zamiast rzucać wyjątek
$nip = Numerik::nip()->tryParse('5260250274');
$nip?->getFormatted();                     // '526-025-02-74'
```

## Tryb strict

Wszystkie identyfikatory przyjmują opcjonalny parametr `strict` (domyślnie `true`), który nigdy nie wpływa na sposób normalizacji danych wejściowych — normalizacja (usuwanie spacji i myślników tam, gdzie format na to pozwala) jest taka sama w obu trybach. Parametr `strict` włącza dodatkowe sprawdzenia sensowności semantycznej: odrzucanie numerów złożonych z samych identycznych cyfr (PESEL, NIP, KRS, VAT-UE) oraz dat urodzenia w przyszłości (PESEL). Dowód osobisty, paszport, REGON, NRB i IBAN nie mają dodatkowych sprawdzeń w trybie strict.

```php
Numerik::nip(strict: false)->isValid('1111111111');  // true
Numerik::nip(strict: true)->isValid('1111111111');   // false — same cyfry
```

## Dokumentacja

Pełna dokumentacja dostępna pod adresem **[numerik.slashlab.pl](https://numerik.slashlab.pl)**

## Port JavaScript / TypeScript

Port TypeScript jest dostępny jako [`@slashlab/numerik-js`](https://github.com/sqrcz/numerik-js). Odzwierciedla API tej biblioteki — te same identyfikatory, te same obiekty wartości, ten sam tryb strict — wraz z integracją Zod.

```bash
npm install @slashlab/numerik-js
```

## Integracja z Laravelem

Dedykowany pakiet Laravel jest dostępny jako [`slashlab/numerik-laravel`](https://github.com/sqrcz/numerik-laravel) (PHP 8.3+, Laravel 12/13). Udostępnia reguły walidacji klasowe i stringowe z komunikatem dla każdej przyczyny błędu, tłumaczeniami angielskimi i polskimi oraz rozszerzonymi ograniczeniami `PeselRule` (`gender`, `bornBefore`, `bornAfter`).

```bash
composer require slashlab/numerik-laravel
```

Pełny opis użycia znajdziesz w [README numerik-laravel](https://github.com/sqrcz/numerik-laravel#readme).

## Historia zmian

Zobacz [CHANGELOG.md](CHANGELOG.md).

## Współpraca

Zobacz [CONTRIBUTING.md](CONTRIBUTING.md).

## Bezpieczeństwo

Zobacz [SECURITY.md](SECURITY.md).

## Licencja

MIT — zobacz [LICENSE](LICENSE).

---

Jeśli ta biblioteka zaoszczędziła Ci czasu → [☕ postaw mi kawę](https://buymeacoffee.com/sqrcz)

---
**Słowa kluczowe:** php, walidacja pesel, sprawdzanie nip, walidacja regon, walidacja krs, walidacja polskich numerów identyfikacyjnych, biblioteka pesel php, nip php, dowód osobisty, paszport, vat-ue, nrb, iban
