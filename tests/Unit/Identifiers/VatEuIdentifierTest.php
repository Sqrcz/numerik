<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\VatEuIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\VatEuFixtures;
use SlashLab\Numerik\ValueObjects\VatEu;

final class VatEuIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validVatEuProvider')]
    public function test_validate_passes_for_valid_vat_eu(string $input): void
    {
        $result = Numerik::vatEu()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validVatEuProvider(): array
    {
        return VatEuFixtures::valid();
    }

    #[DataProvider('invalidVatEuProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::vatEu()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidVatEuProvider(): array
    {
        return VatEuFixtures::invalid();
    }

    #[DataProvider('invalidStrictVatEuProvider')]
    public function test_validate_fails_in_strict_mode(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::vatEu()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidStrictVatEuProvider(): array
    {
        return VatEuFixtures::invalidStrict();
    }

    public function test_validate_passes_for_all_same_digit_nip_when_strict_is_disabled(): void
    {
        $result = Numerik::vatEu(strict: false)->validate('PL1111111111');

        $this->assertTrue($result->isValid);
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::vatEu()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    public function test_validate_does_not_reject_input_of_exactly_32_characters(): void
    {
        $result  = Numerik::vatEu()->validate(str_repeat('1', 32));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_vat_eu(): void
    {
        $this->assertTrue(Numerik::vatEu()->isValid('PL5260250274'));
    }

    public function test_is_valid_returns_false_for_invalid_vat_eu(): void
    {
        $this->assertFalse(Numerik::vatEu()->isValid('PL5260250275'));
    }

    // --- parse() ---

    public function test_parse_returns_vat_eu_value_object(): void
    {
        $this->assertInstanceOf(VatEu::class, Numerik::vatEu()->parse('PL5260250274'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $vatEu = Numerik::vatEu()->parse('PL526-025-02-74');

        $this->assertSame('PL526-025-02-74', $vatEu->getRaw());
    }

    public function test_parse_normalizes_input(): void
    {
        $vatEu = Numerik::vatEu()->parse('PL526-025-02-74');

        $this->assertSame('PL5260250274', $vatEu->getNormalized());
    }

    public function test_parse_normalizes_lowercase_prefix(): void
    {
        $vatEu = Numerik::vatEu()->parse('pl5260250274');

        $this->assertSame('PL5260250274', $vatEu->getNormalized());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $vatEu = Numerik::vatEu()->parse('PL526-025-02-74');

        $this->assertSame('PL5260250274', (string) $vatEu);
    }

    public function test_parse_throws_invalid_format_exception_for_missing_prefix(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::vatEu()->parse('5260250274');
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::vatEu()->parse('PL526025027');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::vatEu()->parse('PL5260250275');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_vat_eu_for_valid_input(): void
    {
        $this->assertInstanceOf(VatEu::class, Numerik::vatEu()->tryParse('PL5260250274'));
    }

    public function test_try_parse_returns_null_for_invalid_input(): void
    {
        $this->assertNull(Numerik::vatEu()->tryParse('PL5260250275'));
    }

    public function test_try_parse_returns_null_for_missing_prefix(): void
    {
        $this->assertNull(Numerik::vatEu()->tryParse('5260250274'));
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::vatEu()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new VatEuIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::vatEu(strict: false)->isStrict());
    }

    public function test_is_instance_of_vat_eu_identifier(): void
    {
        $this->assertInstanceOf(VatEuIdentifier::class, Numerik::vatEu());
    }
}
