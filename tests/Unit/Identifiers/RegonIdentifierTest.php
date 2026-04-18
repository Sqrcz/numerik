<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\RegonType;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\RegonIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\RegonFixtures;
use SlashLab\Numerik\ValueObjects\Regon;

final class RegonIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validRegonProvider')]
    public function test_validate_passes_for_valid_regon(string $input): void
    {
        $result = Numerik::regon()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validRegonProvider(): array
    {
        return RegonFixtures::valid();
    }

    #[DataProvider('invalidRegonProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::regon()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidRegonProvider(): array
    {
        return RegonFixtures::invalid();
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::regon()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_9_digit_regon(): void
    {
        $this->assertTrue(Numerik::regon()->isValid('850518457'));
    }

    public function test_is_valid_returns_true_for_valid_14_digit_regon(): void
    {
        $this->assertTrue(Numerik::regon()->isValid('85051845749370'));
    }

    public function test_is_valid_returns_false_for_invalid_regon(): void
    {
        $this->assertFalse(Numerik::regon()->isValid('850518456'));
    }

    // --- parse() ---

    public function test_parse_returns_regon_value_object(): void
    {
        $this->assertInstanceOf(Regon::class, Numerik::regon()->parse('850518457'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $regon = Numerik::regon()->parse('850 518 457');

        $this->assertSame('850 518 457', $regon->getRaw());
    }

    public function test_parse_normalizes_input(): void
    {
        $regon = Numerik::regon()->parse('850 518 457');

        $this->assertSame('850518457', $regon->getNormalized());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $regon = Numerik::regon()->parse('850 518 457');

        $this->assertSame('850518457', (string) $regon);
    }

    public function test_parse_returns_individual_type_for_9_digit(): void
    {
        $regon = Numerik::regon()->parse('850518457');

        $this->assertSame(RegonType::Individual, $regon->getType());
    }

    public function test_parse_returns_legal_entity_type_for_14_digit(): void
    {
        $regon = Numerik::regon()->parse('85051845749370');

        $this->assertSame(RegonType::LegalEntity, $regon->getType());
    }

    public function test_parse_returns_correct_base_regon_for_9_digit(): void
    {
        $regon = Numerik::regon()->parse('850518457');

        $this->assertSame('850518457', $regon->getBaseRegon());
    }

    public function test_parse_returns_correct_base_regon_for_14_digit(): void
    {
        $regon = Numerik::regon()->parse('85051845749370');

        $this->assertSame('850518457', $regon->getBaseRegon());
    }

    public function test_parse_returns_null_local_unit_suffix_for_9_digit(): void
    {
        $regon = Numerik::regon()->parse('850518457');

        $this->assertNull($regon->getLocalUnitSuffix());
    }

    public function test_parse_returns_correct_local_unit_suffix_for_14_digit(): void
    {
        $regon = Numerik::regon()->parse('85051845749370');

        $this->assertSame('49370', $regon->getLocalUnitSuffix());
    }

    public function test_parse_is_not_local_unit_for_9_digit(): void
    {
        $regon = Numerik::regon()->parse('850518457');

        $this->assertFalse($regon->isLocalUnit());
    }

    public function test_parse_is_local_unit_for_14_digit(): void
    {
        $regon = Numerik::regon()->parse('85051845749370');

        $this->assertTrue($regon->isLocalUnit());
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::regon()->parse('85051845');
    }

    public function test_parse_throws_invalid_format_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::regon()->parse('85051845A');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::regon()->parse('850518456');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_regon_for_valid_input(): void
    {
        $this->assertInstanceOf(Regon::class, Numerik::regon()->tryParse('850518457'));
    }

    public function test_try_parse_returns_null_for_invalid_checksum(): void
    {
        $this->assertNull(Numerik::regon()->tryParse('850518456'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::regon()->tryParse('not-a-regon'));
    }

    public function test_validate_does_not_reject_input_of_exactly_32_characters(): void
    {
        $result  = Numerik::regon()->validate(str_repeat('8', 32));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::regon()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new RegonIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::regon(strict: false)->isStrict());
    }

    public function test_is_instance_of_regon_identifier(): void
    {
        $this->assertInstanceOf(RegonIdentifier::class, Numerik::regon());
    }
}
