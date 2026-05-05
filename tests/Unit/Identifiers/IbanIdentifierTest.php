<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\IbanIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\IbanFixtures;
use SlashLab\Numerik\ValueObjects\Iban;

final class IbanIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validIbanProvider')]
    public function test_validate_passes_for_valid_iban(string $input): void
    {
        $result = Numerik::iban()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validIbanProvider(): array
    {
        return IbanFixtures::valid();
    }

    #[DataProvider('invalidIbanProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::iban()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidIbanProvider(): array
    {
        return IbanFixtures::invalid();
    }

    public function test_validate_rejects_bare_nrb_without_prefix(): void
    {
        $result = Numerik::iban()->validate('61102010260000000000000000');

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidFormat));
    }

    public function test_validate_fails_when_input_exceeds_40_characters(): void
    {
        $result = Numerik::iban()->validate(str_repeat('1', 41));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    public function test_validate_does_not_reject_input_of_exactly_40_characters(): void
    {
        $result  = Numerik::iban()->validate(str_repeat('1', 40));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_iban(): void
    {
        $this->assertTrue(Numerik::iban()->isValid('PL61102010260000000000000000'));
    }

    public function test_is_valid_returns_false_for_invalid_checksum(): void
    {
        $this->assertFalse(Numerik::iban()->isValid('PL62102010260000000000000000'));
    }

    public function test_is_valid_returns_false_for_bare_nrb(): void
    {
        $this->assertFalse(Numerik::iban()->isValid('61102010260000000000000000'));
    }

    // --- parse() ---

    public function test_parse_returns_iban_value_object(): void
    {
        $this->assertInstanceOf(Iban::class, Numerik::iban()->parse('PL61102010260000000000000000'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $iban = Numerik::iban()->parse('PL61 1020 1026 0000 0000 0000 0000');

        $this->assertSame('PL61 1020 1026 0000 0000 0000 0000', $iban->getRaw());
    }

    public function test_parse_normalizes_spaced_input(): void
    {
        $iban = Numerik::iban()->parse('PL61 1020 1026 0000 0000 0000 0000');

        $this->assertSame('PL61102010260000000000000000', $iban->getNormalized());
    }

    public function test_parse_normalizes_lowercase_prefix(): void
    {
        $iban = Numerik::iban()->parse('pl61102010260000000000000000');

        $this->assertSame('PL61102010260000000000000000', $iban->getNormalized());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $iban = Numerik::iban()->parse('PL61 1020 1026 0000 0000 0000 0000');

        $this->assertSame('PL61102010260000000000000000', (string) $iban);
    }

    public function test_parse_throws_invalid_format_exception_for_missing_prefix(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::iban()->parse('61102010260000000000000000');
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::iban()->parse('PL6110201026000000000000000');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::iban()->parse('PL62102010260000000000000000');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_iban_for_valid_input(): void
    {
        $this->assertInstanceOf(Iban::class, Numerik::iban()->tryParse('PL61102010260000000000000000'));
    }

    public function test_try_parse_returns_null_for_invalid_checksum(): void
    {
        $this->assertNull(Numerik::iban()->tryParse('PL62102010260000000000000000'));
    }

    public function test_try_parse_returns_null_for_missing_prefix(): void
    {
        $this->assertNull(Numerik::iban()->tryParse('61102010260000000000000000'));
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::iban()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new IbanIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::iban(strict: false)->isStrict());
    }

    public function test_is_instance_of_iban_identifier(): void
    {
        $this->assertInstanceOf(IbanIdentifier::class, Numerik::iban());
    }
}
