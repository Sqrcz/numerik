<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\IdCardIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\IdCardFixtures;
use SlashLab\Numerik\ValueObjects\IdCard;

final class IdCardIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validIdCardProvider')]
    public function test_validate_passes_for_valid_id_card(string $input): void
    {
        $result = Numerik::idCard()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validIdCardProvider(): array
    {
        return IdCardFixtures::valid();
    }

    #[DataProvider('invalidIdCardProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::idCard()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidIdCardProvider(): array
    {
        return IdCardFixtures::invalid();
    }

    public function test_validate_fails_when_input_exceeds_32_characters(): void
    {
        $result = Numerik::idCard()->validate(str_repeat('1', 33));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    public function test_validate_does_not_reject_input_of_exactly_32_characters(): void
    {
        $result  = Numerik::idCard()->validate(str_repeat('1', 32));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_id_card(): void
    {
        $this->assertTrue(Numerik::idCard()->isValid('ABC123454'));
    }

    public function test_is_valid_returns_false_for_wrong_checksum(): void
    {
        $this->assertFalse(Numerik::idCard()->isValid('ABC123453'));
    }

    public function test_is_valid_returns_false_for_invalid_series(): void
    {
        $this->assertFalse(Numerik::idCard()->isValid('OBC123456'));
    }

    // --- parse() ---

    public function test_parse_returns_id_card_value_object(): void
    {
        $this->assertInstanceOf(IdCard::class, Numerik::idCard()->parse('ABC123454'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $idCard = Numerik::idCard()->parse('abc-123-454');

        $this->assertSame('abc-123-454', $idCard->getRaw());
    }

    public function test_parse_normalizes_lowercase_with_hyphens(): void
    {
        $idCard = Numerik::idCard()->parse('abc-123-454');

        $this->assertSame('ABC123454', $idCard->getNormalized());
    }

    public function test_parse_to_string_returns_normalized(): void
    {
        $idCard = Numerik::idCard()->parse('abc-123-454');

        $this->assertSame('ABC123454', (string) $idCard);
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::idCard()->parse('ABC12345');
    }

    public function test_parse_throws_invalid_format_exception_for_letter_o_in_series(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::idCard()->parse('OBC123456');
    }

    public function test_parse_throws_invalid_format_exception_for_digit_in_series(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::idCard()->parse('1BC123456');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::idCard()->parse('ABC123453');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_id_card_for_valid_input(): void
    {
        $this->assertInstanceOf(IdCard::class, Numerik::idCard()->tryParse('ABC123454'));
    }

    public function test_try_parse_returns_null_for_invalid_checksum(): void
    {
        $this->assertNull(Numerik::idCard()->tryParse('ABC123453'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::idCard()->tryParse('OBC123456'));
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::idCard()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new IdCardIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::idCard(strict: false)->isStrict());
    }

    public function test_is_instance_of_id_card_identifier(): void
    {
        $this->assertInstanceOf(IdCardIdentifier::class, Numerik::idCard());
    }
}
