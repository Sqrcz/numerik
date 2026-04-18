<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\NipIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\NipFixtures;
use SlashLab\Numerik\ValueObjects\Nip;

final class NipIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validNipProvider')]
    public function test_validate_passes_for_valid_nip(string $input): void
    {
        $result = Numerik::nip()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validNipProvider(): array
    {
        return NipFixtures::valid();
    }

    #[DataProvider('invalidNipProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::nip()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidNipProvider(): array
    {
        return NipFixtures::invalid();
    }

    #[DataProvider('invalidStrictNipProvider')]
    public function test_validate_fails_in_strict_mode(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::nip()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidStrictNipProvider(): array
    {
        return NipFixtures::invalidStrict();
    }

    public function test_validate_passes_for_all_same_digit_when_strict_is_disabled(): void
    {
        $result = Numerik::nip(strict: false)->validate('1111111111');

        $this->assertTrue($result->isValid);
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::nip()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_nip(): void
    {
        $this->assertTrue(Numerik::nip()->isValid('5260250274'));
    }

    public function test_is_valid_returns_false_for_invalid_nip(): void
    {
        $this->assertFalse(Numerik::nip()->isValid('5260250275'));
    }

    // --- parse() ---

    public function test_parse_returns_nip_value_object(): void
    {
        $this->assertInstanceOf(Nip::class, Numerik::nip()->parse('5260250274'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $nip = Numerik::nip()->parse('526-025-02-74');

        $this->assertSame('526-025-02-74', $nip->getRaw());
    }

    public function test_parse_normalizes_input(): void
    {
        $nip = Numerik::nip()->parse('526-025-02-74');

        $this->assertSame('5260250274', $nip->getNormalized());
    }

    public function test_parse_returns_correct_formatted_output(): void
    {
        $nip = Numerik::nip()->parse('5260250274');

        $this->assertSame('526-025-02-74', $nip->getFormatted());
    }

    public function test_parse_returns_correct_tax_office_code(): void
    {
        $nip = Numerik::nip()->parse('5260250274');

        $this->assertSame('526', $nip->getTaxOfficeCode());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $nip = Numerik::nip()->parse('526-025-02-74');

        $this->assertSame('5260250274', (string) $nip);
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::nip()->parse('526025027');
    }

    public function test_parse_throws_invalid_format_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::nip()->parse('526ABC0274');
    }

    public function test_parse_throws_invalid_format_exception_for_000_tax_office(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::nip()->parse('0001234567');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::nip()->parse('5260250275');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_nip_for_valid_input(): void
    {
        $this->assertInstanceOf(Nip::class, Numerik::nip()->tryParse('5260250274'));
    }

    public function test_try_parse_returns_null_for_invalid_input(): void
    {
        $this->assertNull(Numerik::nip()->tryParse('5260250275'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::nip()->tryParse('not-a-nip'));
    }

    public function test_parse_returns_correct_formatted_alternative(): void
    {
        $nip = Numerik::nip()->parse('5260250274');

        $this->assertSame('526-02-50-274', $nip->getFormattedAlternative());
    }

    public function test_validate_does_not_reject_input_of_exactly_32_characters(): void
    {
        $result  = Numerik::nip()->validate(str_repeat('5', 32));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::nip()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new NipIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::nip(strict: false)->isStrict());
    }

    public function test_is_instance_of_nip_identifier(): void
    {
        $this->assertInstanceOf(NipIdentifier::class, Numerik::nip());
    }
}
