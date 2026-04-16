<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\Gender;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidDateException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\PeselIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\PeselFixtures;
use SlashLab\Numerik\ValueObjects\Pesel;

final class PeselIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validPeselProvider')]
    public function test_validate_passes_for_valid_pesel(string $input): void
    {
        $result = Numerik::pesel()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validPeselProvider(): array
    {
        return PeselFixtures::valid();
    }

    #[DataProvider('invalidPeselProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::pesel()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidPeselProvider(): array
    {
        return PeselFixtures::invalid();
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::pesel()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    public function test_validate_fails_for_future_date_in_strict_mode(): void
    {
        // 30210100018 = 2030-01-01, valid checksum but future date
        $result = Numerik::pesel(strict: true)->validate('30210100018');

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::FutureDate));
    }

    public function test_validate_passes_for_future_date_in_non_strict_mode(): void
    {
        $result = Numerik::pesel(strict: false)->validate('30210100018');

        $this->assertTrue($result->isValid);
    }

    public function test_validate_fails_for_all_same_digit_in_strict_mode(): void
    {
        $result = Numerik::pesel(strict: true)->validate('22222222222');

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::AllSameDigit));
    }

    public function test_validate_passes_for_all_same_digit_in_non_strict_mode(): void
    {
        $result = Numerik::pesel(strict: false)->validate('22222222222');

        $this->assertTrue($result->isValid);
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_pesel(): void
    {
        $this->assertTrue(Numerik::pesel()->isValid('44051401458'));
    }

    public function test_is_valid_returns_false_for_invalid_checksum(): void
    {
        $this->assertFalse(Numerik::pesel()->isValid('44051401459'));
    }

    public function test_is_valid_returns_false_for_wrong_length(): void
    {
        $this->assertFalse(Numerik::pesel()->isValid('4405140145'));
    }

    // --- parse() ---

    public function test_parse_returns_pesel_value_object(): void
    {
        $this->assertInstanceOf(Pesel::class, Numerik::pesel()->parse('44051401458'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $pesel = Numerik::pesel()->parse('44051 401458');

        $this->assertSame('44051 401458', $pesel->getRaw());
    }

    public function test_parse_normalizes_input(): void
    {
        $pesel = Numerik::pesel()->parse('44051 401458');

        $this->assertSame('44051401458', $pesel->getNormalized());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $pesel = Numerik::pesel()->parse('44051 401458');

        $this->assertSame('44051401458', (string) $pesel);
    }

    public function test_parse_returns_correct_birth_date_for_1900s(): void
    {
        $pesel = Numerik::pesel()->parse('44051401458');

        $this->assertSame('1944-05-14', $pesel->getBirthDate()->format('Y-m-d'));
    }

    public function test_parse_returns_correct_birth_date_for_2000s(): void
    {
        $pesel = Numerik::pesel()->parse('02210213452');

        $this->assertSame('2002-01-02', $pesel->getBirthDate()->format('Y-m-d'));
    }

    public function test_parse_returns_correct_birth_date_for_1800s(): void
    {
        $pesel = Numerik::pesel()->parse('98831512348');

        $this->assertSame('1898-03-15', $pesel->getBirthDate()->format('Y-m-d'));
    }

    public function test_parse_returns_male_gender(): void
    {
        $pesel = Numerik::pesel()->parse('44051401458'); // ordinal digit[9]=5 (odd)

        $this->assertSame(Gender::Male, $pesel->getGender());
    }

    public function test_parse_returns_female_gender(): void
    {
        $pesel = Numerik::pesel()->parse('90123112340'); // ordinal digit[9]=4 (even)

        $this->assertSame(Gender::Female, $pesel->getGender());
    }

    public function test_is_male_returns_true_for_male(): void
    {
        $this->assertTrue(Numerik::pesel()->parse('44051401458')->isMale());
    }

    public function test_is_female_returns_true_for_female(): void
    {
        $this->assertTrue(Numerik::pesel()->parse('90123112340')->isFemale());
    }

    public function test_is_male_returns_false_for_female(): void
    {
        $this->assertFalse(Numerik::pesel()->parse('90123112340')->isMale());
    }

    public function test_is_female_returns_false_for_male(): void
    {
        $this->assertFalse(Numerik::pesel()->parse('44051401458')->isFemale());
    }

    public function test_parse_returns_correct_ordinal_number(): void
    {
        $pesel = Numerik::pesel()->parse('44051401458'); // digits[6..9] = 0145

        $this->assertSame(145, $pesel->getOrdinalNumber());
    }

    public function test_get_age_returns_non_negative_integer(): void
    {
        $age = Numerik::pesel()->parse('44051401458')->getAge();

        $this->assertGreaterThanOrEqual(0, $age);
    }

    public function test_is_adult_returns_true_for_person_born_in_1944(): void
    {
        $this->assertTrue(Numerik::pesel()->parse('44051401458')->isAdult());
    }

    public function test_is_adult_returns_false_for_person_born_in_2020(): void
    {
        // 20230112351 = 2020-03-01, male; will be a minor until 2038
        $this->assertFalse(Numerik::pesel()->parse('20230112351')->isAdult());
    }

    public function test_get_century_returns_1900_for_1940s_birth(): void
    {
        $this->assertSame(1900, Numerik::pesel()->parse('44051401458')->getCentury());
    }

    public function test_get_century_returns_2000_for_2000s_birth(): void
    {
        $this->assertSame(2000, Numerik::pesel()->parse('02210213452')->getCentury());
    }

    public function test_get_century_returns_1800_for_1800s_birth(): void
    {
        $this->assertSame(1800, Numerik::pesel()->parse('98831512348')->getCentury());
    }

    // --- parse() exceptions ---

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::pesel()->parse('4405140145');
    }

    public function test_parse_throws_invalid_format_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::pesel()->parse('4405140145A');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::pesel()->parse('44051401459');
    }

    public function test_parse_throws_invalid_date_exception_for_invalid_date(): void
    {
        $this->expectException(InvalidDateException::class);

        Numerik::pesel()->parse('44023101452'); // Feb 31
    }

    public function test_parse_throws_invalid_date_exception_for_invalid_month(): void
    {
        $this->expectException(InvalidDateException::class);

        Numerik::pesel()->parse('44001401453'); // month encoding 00
    }

    public function test_parse_throws_invalid_date_exception_for_future_date_in_strict_mode(): void
    {
        $this->expectException(InvalidDateException::class);

        Numerik::pesel(strict: true)->parse('30210100018'); // 2030-01-01
    }

    public function test_parse_succeeds_for_future_date_in_non_strict_mode(): void
    {
        $pesel = Numerik::pesel(strict: false)->parse('30210100018');

        $this->assertSame('2030-01-01', $pesel->getBirthDate()->format('Y-m-d'));
    }

    // --- tryParse() ---

    public function test_try_parse_returns_pesel_for_valid_input(): void
    {
        $this->assertInstanceOf(Pesel::class, Numerik::pesel()->tryParse('44051401458'));
    }

    public function test_try_parse_returns_null_for_invalid_checksum(): void
    {
        $this->assertNull(Numerik::pesel()->tryParse('44051401459'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::pesel()->tryParse('not-a-pesel'));
    }

    public function test_try_parse_returns_null_for_invalid_date(): void
    {
        $this->assertNull(Numerik::pesel()->tryParse('44023101452'));
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::pesel()->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::pesel(strict: false)->isStrict());
    }

    public function test_is_instance_of_pesel_identifier(): void
    {
        $this->assertInstanceOf(PeselIdentifier::class, Numerik::pesel());
    }
}
