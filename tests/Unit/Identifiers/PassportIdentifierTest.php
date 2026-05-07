<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\PassportIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\PassportFixtures;
use SlashLab\Numerik\ValueObjects\Passport;

final class PassportIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validPassportProvider')]
    public function test_validate_passes_for_valid_passport(string $input): void
    {
        $result = Numerik::passport()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validPassportProvider(): array
    {
        return PassportFixtures::valid();
    }

    #[DataProvider('invalidPassportProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::passport()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidPassportProvider(): array
    {
        return PassportFixtures::invalid();
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::passport()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    public function test_validate_does_not_reject_input_of_exactly_32_characters(): void
    {
        $result  = Numerik::passport()->validate(str_repeat('1', 32));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_passport(): void
    {
        $this->assertTrue(Numerik::passport()->isValid('AB1234564'));
    }

    public function test_is_valid_returns_false_for_wrong_checksum(): void
    {
        $this->assertFalse(Numerik::passport()->isValid('AB1234563'));
    }

    public function test_is_valid_returns_false_for_digit_in_series(): void
    {
        $this->assertFalse(Numerik::passport()->isValid('1B1234564'));
    }

    // --- parse() ---

    public function test_parse_returns_passport_value_object(): void
    {
        $this->assertInstanceOf(Passport::class, Numerik::passport()->parse('AB1234564'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $passport = Numerik::passport()->parse('ab 123456 4');

        $this->assertSame('ab 123456 4', $passport->getRaw());
    }

    public function test_parse_normalizes_lowercase_with_spaces(): void
    {
        $passport = Numerik::passport()->parse('ab 123456 4');

        $this->assertSame('AB1234564', $passport->getNormalized());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $passport = Numerik::passport()->parse('ab 123456 4');

        $this->assertSame('AB1234564', (string) $passport);
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::passport()->parse('AB123456');
    }

    public function test_parse_throws_invalid_format_exception_for_digit_in_series(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::passport()->parse('1B1234564');
    }

    public function test_parse_throws_invalid_format_exception_for_letter_in_number(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::passport()->parse('AB123456A');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::passport()->parse('AB1234563');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_passport_for_valid_input(): void
    {
        $this->assertInstanceOf(Passport::class, Numerik::passport()->tryParse('AB1234564'));
    }

    public function test_try_parse_returns_null_for_invalid_checksum(): void
    {
        $this->assertNull(Numerik::passport()->tryParse('AB1234563'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::passport()->tryParse('1B1234564'));
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::passport()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new PassportIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::passport(strict: false)->isStrict());
    }

    public function test_is_instance_of_passport_identifier(): void
    {
        $this->assertInstanceOf(PassportIdentifier::class, Numerik::passport());
    }
}
