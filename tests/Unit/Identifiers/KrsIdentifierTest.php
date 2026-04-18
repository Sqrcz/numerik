<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\KrsIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\KrsFixtures;
use SlashLab\Numerik\ValueObjects\Krs;

final class KrsIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validKrsProvider')]
    public function test_validate_passes_for_valid_krs(string $input): void
    {
        $result = Numerik::krs()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validKrsProvider(): array
    {
        return KrsFixtures::valid();
    }

    #[DataProvider('invalidKrsProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::krs()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidKrsProvider(): array
    {
        return KrsFixtures::invalid();
    }

    #[DataProvider('invalidStrictKrsProvider')]
    public function test_validate_fails_in_strict_mode(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::krs()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidStrictKrsProvider(): array
    {
        return KrsFixtures::invalidStrict();
    }

    public function test_validate_passes_for_all_same_digit_when_strict_is_disabled(): void
    {
        $result = Numerik::krs(strict: false)->validate('1111111111');

        $this->assertTrue($result->isValid);
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::krs()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_krs(): void
    {
        $this->assertTrue(Numerik::krs()->isValid('0000127206'));
    }

    public function test_is_valid_returns_false_for_invalid_krs(): void
    {
        $this->assertFalse(Numerik::krs()->isValid('0000000000'));
    }

    // --- parse() ---

    public function test_parse_returns_krs_value_object(): void
    {
        $this->assertInstanceOf(Krs::class, Numerik::krs()->parse('0000127206'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $krs = Numerik::krs()->parse('127206');

        $this->assertSame('127206', $krs->getRaw());
    }

    public function test_parse_normalizes_input(): void
    {
        $krs = Numerik::krs()->parse('127 206');

        $this->assertSame('127206', $krs->getNormalized());
    }

    public function test_parse_returns_correct_formatted_output(): void
    {
        $krs = Numerik::krs()->parse('127206');

        $this->assertSame('0000127206', $krs->getFormatted());
    }

    public function test_parse_returns_correct_numeric_value(): void
    {
        $krs = Numerik::krs()->parse('0000127206');

        $this->assertSame(127206, $krs->getNumericValue());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $krs = Numerik::krs()->parse('127206');

        $this->assertSame('127206', (string) $krs);
    }

    public function test_parse_throws_invalid_format_exception_for_all_zeros(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::krs()->parse('0000000000');
    }

    public function test_parse_throws_invalid_format_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::krs()->parse('KRS1234567');
    }

    public function test_parse_throws_invalid_format_exception_for_too_long(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::krs()->parse('00001272060');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_krs_for_valid_input(): void
    {
        $this->assertInstanceOf(Krs::class, Numerik::krs()->tryParse('0000127206'));
    }

    public function test_try_parse_returns_null_for_invalid_input(): void
    {
        $this->assertNull(Numerik::krs()->tryParse('0000000000'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::krs()->tryParse('not-a-krs'));
    }

    public function test_validate_does_not_reject_input_of_exactly_32_characters(): void
    {
        $result  = Numerik::krs()->validate(str_repeat('1', 32));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::krs()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new KrsIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::krs(strict: false)->isStrict());
    }

    public function test_is_instance_of_krs_identifier(): void
    {
        $this->assertInstanceOf(KrsIdentifier::class, Numerik::krs());
    }
}
