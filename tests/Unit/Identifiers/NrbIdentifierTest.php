<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Identifiers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Identifiers\NrbIdentifier;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Tests\Fixtures\NrbFixtures;
use SlashLab\Numerik\ValueObjects\Nrb;

final class NrbIdentifierTest extends TestCase
{
    // --- validate() ---

    #[DataProvider('validNrbProvider')]
    public function test_validate_passes_for_valid_nrb(string $input): void
    {
        $result = Numerik::nrb()->validate($input);

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    /** @return array<string, array{string}> */
    public static function validNrbProvider(): array
    {
        return NrbFixtures::valid();
    }

    #[DataProvider('invalidNrbProvider')]
    public function test_validate_fails_with_correct_reason(string $input, ValidationFailureReason $reason): void
    {
        $result = Numerik::nrb()->validate($input);

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason($reason));
    }

    /** @return array<string, array{string, ValidationFailureReason}> */
    public static function invalidNrbProvider(): array
    {
        return NrbFixtures::invalid();
    }

    public function test_validate_fails_when_input_exceeds_40_characters(): void
    {
        $result = Numerik::nrb()->validate(str_repeat('1', 41));

        $this->assertTrue($result->isFailed());
        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidLength));
    }

    public function test_validate_does_not_reject_input_of_exactly_40_characters(): void
    {
        $result  = Numerik::nrb()->validate(str_repeat('1', 40));
        $failure = $result->getFirstFailure();

        $this->assertNotNull($failure);
        $this->assertStringNotContainsString('exceeds maximum', $failure->message);
    }

    // --- isValid() ---

    public function test_is_valid_returns_true_for_valid_nrb(): void
    {
        $this->assertTrue(Numerik::nrb()->isValid('61102010260000000000000000'));
    }

    public function test_is_valid_returns_false_for_invalid_nrb(): void
    {
        $this->assertFalse(Numerik::nrb()->isValid('62102010260000000000000000'));
    }

    // --- parse() ---

    public function test_parse_returns_nrb_value_object(): void
    {
        $this->assertInstanceOf(Nrb::class, Numerik::nrb()->parse('61102010260000000000000000'));
    }

    public function test_parse_preserves_raw_input(): void
    {
        $nrb = Numerik::nrb()->parse('61 1020 1026 0000 0000 0000 0000');

        $this->assertSame('61 1020 1026 0000 0000 0000 0000', $nrb->getRaw());
    }

    public function test_parse_normalizes_spaced_input(): void
    {
        $nrb = Numerik::nrb()->parse('61 1020 1026 0000 0000 0000 0000');

        $this->assertSame('61102010260000000000000000', $nrb->getNormalized());
    }

    public function test_parse_normalizes_iban_format(): void
    {
        $nrb = Numerik::nrb()->parse('PL61102010260000000000000000');

        $this->assertSame('61102010260000000000000000', $nrb->getNormalized());
    }

    public function test_parse_normalizes_iban_with_spaces(): void
    {
        $nrb = Numerik::nrb()->parse('PL61 1020 1026 0000 0000 0000 0000');

        $this->assertSame('61102010260000000000000000', $nrb->getNormalized());
    }

    public function test_parse_throws_invalid_format_exception_for_wrong_length(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::nrb()->parse('6110201026000000000000000');
    }

    public function test_parse_throws_invalid_format_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidFormatException::class);

        Numerik::nrb()->parse('61102010260000000000000ABC');
    }

    public function test_parse_throws_invalid_checksum_exception(): void
    {
        $this->expectException(InvalidChecksumException::class);

        Numerik::nrb()->parse('62102010260000000000000000');
    }

    // --- tryParse() ---

    public function test_try_parse_returns_nrb_for_valid_input(): void
    {
        $this->assertInstanceOf(Nrb::class, Numerik::nrb()->tryParse('61102010260000000000000000'));
    }

    public function test_try_parse_returns_null_for_invalid_checksum(): void
    {
        $this->assertNull(Numerik::nrb()->tryParse('62102010260000000000000000'));
    }

    public function test_try_parse_returns_null_for_invalid_format(): void
    {
        $this->assertNull(Numerik::nrb()->tryParse('not-an-nrb'));
    }

    // --- isStrict() ---

    public function test_strict_mode_is_enabled_by_default(): void
    {
        $this->assertTrue(Numerik::nrb()->isStrict());
    }

    public function test_strict_mode_is_enabled_by_default_when_constructed_directly(): void
    {
        $this->assertTrue((new NrbIdentifier())->isStrict());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::nrb(strict: false)->isStrict());
    }

    public function test_is_instance_of_nrb_identifier(): void
    {
        $this->assertInstanceOf(NrbIdentifier::class, Numerik::nrb());
    }
}
