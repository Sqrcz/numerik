<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\Gender;
use SlashLab\Numerik\ValueObjects\Pesel;

final class PeselTest extends TestCase
{
    private function make(
        string $raw,
        string $normalized,
        DateTimeImmutable $birthDate,
        Gender $gender,
        int $ordinalNumber,
    ): Pesel {
        return new Pesel(
            raw: $raw,
            normalized: $normalized,
            birthDate: $birthDate,
            gender: $gender,
            ordinalNumber: $ordinalNumber,
        );
    }

    public function test_get_raw_returns_original_input(): void
    {
        $pesel = $this->make('44051 401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertSame('44051 401458', $pesel->getRaw());
    }

    public function test_get_normalized_returns_digits_only(): void
    {
        $pesel = $this->make('44051 401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertSame('44051401458', $pesel->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertSame('44051401458', (string) $pesel);
    }

    public function test_get_birth_date_returns_correct_date(): void
    {
        $birthDate = new DateTimeImmutable('1944-05-14');
        $pesel     = $this->make('44051401458', '44051401458', $birthDate, Gender::Male, 145);

        $this->assertSame('1944-05-14', $pesel->getBirthDate()->format('Y-m-d'));
    }

    public function test_get_gender_returns_male(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertSame(Gender::Male, $pesel->getGender());
    }

    public function test_get_gender_returns_female(): void
    {
        $pesel = $this->make('90123112340', '90123112340', new DateTimeImmutable('1990-12-31'), Gender::Female, 1234);

        $this->assertSame(Gender::Female, $pesel->getGender());
    }

    public function test_is_male_returns_true_for_male(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertTrue($pesel->isMale());
        $this->assertFalse($pesel->isFemale());
    }

    public function test_is_female_returns_true_for_female(): void
    {
        $pesel = $this->make('90123112340', '90123112340', new DateTimeImmutable('1990-12-31'), Gender::Female, 1234);

        $this->assertTrue($pesel->isFemale());
        $this->assertFalse($pesel->isMale());
    }

    public function test_get_ordinal_number_returns_correct_value(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertSame(145, $pesel->getOrdinalNumber());
    }

    public function test_get_age_returns_non_negative_integer(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertGreaterThanOrEqual(0, $pesel->getAge());
    }

    public function test_is_adult_returns_true_for_person_born_in_1944(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertTrue($pesel->isAdult());
    }

    public function test_is_adult_returns_false_for_person_born_recently(): void
    {
        $birthDate = new DateTimeImmutable('today -5 years');
        $pesel     = $this->make('', '', $birthDate, Gender::Male, 1);

        $this->assertFalse($pesel->isAdult());
    }

    public function test_get_century_returns_1900_for_1940s(): void
    {
        $pesel = $this->make('44051401458', '44051401458', new DateTimeImmutable('1944-05-14'), Gender::Male, 145);

        $this->assertSame(1900, $pesel->getCentury());
    }

    public function test_get_century_returns_2000_for_2000s(): void
    {
        $pesel = $this->make('02210213452', '02210213452', new DateTimeImmutable('2002-01-02'), Gender::Male, 1345);

        $this->assertSame(2000, $pesel->getCentury());
    }

    public function test_get_century_returns_1800_for_1800s(): void
    {
        $pesel = $this->make('98831512348', '98831512348', new DateTimeImmutable('1898-03-15'), Gender::Female, 1234);

        $this->assertSame(1800, $pesel->getCentury());
    }
}
