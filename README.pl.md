# Numerik

[![Tests](https://github.com/sqrcz/numerik/actions/workflows/tests.yml/badge.svg)](https://github.com/sqrcz/numerik/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%2010-brightgreen.svg)](https://phpstan.org)
[![Latest Version](https://img.shields.io/packagist/v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![PHP Version](https://img.shields.io/packagist/php-v/slashlab/numerik.svg)](https://packagist.org/packages/slashlab/numerik)
[![License](https://img.shields.io/packagist/l/slashlab/numerik.svg)](LICENSE)

[English](README.md) | **Polski**

Nowoczesna biblioteka PHP 8.3+ do walidacji i parsowania polskich numerów identyfikacyjnych — PESEL, NIP, REGON, KRS, NRB, VAT-EU, IBAN, dowód osobisty oraz paszport. Zwraca czytelne, niemutowalne obiekty danych (`readonly`), precyzyjnie wskazuje powody błędów i nie posiada żadnych zewnętrznych zależności produkcyjnych.

## Instalacja

```bash
composer require slashlab/numerik
```

## Szybki start

```php
use SlashLab\Numerik\Numerik;

// Proste sprawdzenie poprawności (zwraca bool)
Numerik::pesel()->isValid('92060512186'); // true
Numerik::nip()->isValid('5260250274');    // true

// Szczegółowy wynik walidacji z precyzyjnym powodem błędu
$result = Numerik::pesel()->validate('92060512185'); // błędna cyfra kontrolna
$result->isFailed();                      // true
$result->getFirstFailure()->reason;       // ValidationFailureReason::InvalidChecksum

// Parsowanie do czytelnego obiektu danych
$pesel = Numerik::pesel()->parse('92060512186');
$pesel->getBirthDate()->format('Y-m-d');  // '1992-06-05'
$pesel->getGender();                      // Gender::Female

// Bezpieczne parsowanie (tryParse) — zwraca null w przypadku błędu zamiast rzucać wyjątek
$nip = Numerik::nip()->tryParse('5260250274');
$nip?->getFormatted();                    // '526-025-02-74'
```

## Tryb ścisły (Strict Mode)

Wszystkie identyfikatory przyjmują opcjonalny parametr `strict` (domyślnie `true`). Nie wpływa on na sposób normalizacji danych wejściowych — usuwanie spacji oraz myślników (tam, gdzie format na to pozwala) działa identycznie w obu trybach.

Parametr `strict` uruchamia dodatkowe, semantyczne sprawdzenie wiarygodności numeru: odrzuca ciągi składające się z tej samej powtórzonej cyfry (dla PESEL, NIP, KRS, VAT-EU) oraz daty urodzenia z przyszłości (dla PESEL). Dowód osobisty, paszport, REGON, NRB oraz IBAN nie posiadają dodatkowych reguł w trybie ścisłym.

```php
Numerik::nip(strict: false)->isValid('1111111111'); // true (poprawna suma kontrolna)
Numerik::nip(strict: true)->isValid('1111111111');  // false — ciąg identycznych cyfr
```

## Obsługiwane identyfikatory

* **PESEL** — waliduje i parsuje numer PESEL; wyodrębnia datę urodzenia, płeć, stulecie, wiek oraz numer porządkowy.
* **NIP** — waliduje numery identyfikacji podatkowej; zwraca kod urzędu skarbowego oraz sformatowaną postać wyświetlaną.
* **VAT-EU** — waliduje unijne numery VAT polskich podatników (`PL` + NIP) algorytmem MOD-11.
* **REGON** — obsługuje warianty 9-cyfrowe (podmiot bazowy) oraz 14-cyfrowe (jednostka lokalna).
* **KRS** — waliduje numery w rejestrze sądowym z automatyczną normalizacją zer wiodących.
* **NRB i IBAN** — waliduje polskie numery rachunków bankowych algorytmem MOD-97; zwraca numer rozliczeniowy, kod banku i numer rachunku.
* **Dowód osobisty i Paszport** — waliduje serię i numer dokumentu zgodnie ze standardem sumy kontrolnej ICAO 9303.

## Dokumentacja

Pełna dokumentacja w języku polskim jest dostępna pod adresem **[numerik.slashlab.pl/pl/](https://numerik.slashlab.pl/pl/)**.

## Port JavaScript / TypeScript

Wersja dla środowiska TypeScript/JavaScript jest dostępna jako pakiet [`@slashlab/numerik-js`](https://www.npmjs.com/package/@slashlab/numerik-js). Odwzorowuje ona API tej biblioteki — obsługuje te same identyfikatory, zwraca analogiczne obiekty danych, wspiera tryb ścisły oraz zawiera gotową integrację z biblioteką Zod.

```bash
npm install @slashlab/numerik-js
```

## Integracja z Laravel

Dedykowany pakiet dla frameworka Laravel jest dostępny jako [`slashlab/numerik-laravel`](https://packagist.org/packages/slashlab/numerik-laravel) (PHP 8.3+, Laravel 12/13). Udostępnia gotowe reguły walidacji (w składni obiektowej oraz tekstowej) z komunikatami błędów dopasowanymi do konkretnej przyczyny odrzucenia, wbudowane tłumaczenia (PL/EN) oraz rozszerzone ograniczenia dla reguły `PeselRule` (`gender`, `bornBefore`, `bornAfter`).

```bash
composer require slashlab/numerik-laravel
```

Szczegółowe informacje o użyciu znajdują się w [dokumentacji pakietu numerik-laravel](https://numerik.slashlab.pl/pl/integrations/laravel/).

## Dziennik zmian (Changelog)

Zobacz plik [CHANGELOG.md](CHANGELOG.md).

## Współtworzenie (Contributing)

Zobacz plik [CONTRIBUTING.md](CONTRIBUTING.md).

## Bezpieczeństwo

Zobacz plik [SECURITY.md](SECURITY.md).

## Licencja

Licencja MIT — szczegóły w pliku [LICENSE](LICENSE).

---

Jeśli ta biblioteka zaoszczędziła Twój czas → [☕ Postaw mi kawę](https://buymeacoffee.com/sqrcz)
