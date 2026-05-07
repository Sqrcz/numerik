<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\IdCard;

final class IdCardTest extends TestCase
{
    private function make(string $raw, string $normalized): IdCard
    {
        return new IdCard(raw: $raw, normalized: $normalized);
    }

    public function test_get_raw_returns_original_input(): void
    {
        $idCard = $this->make('abc-123-454', 'ABC123454');

        $this->assertSame('abc-123-454', $idCard->getRaw());
    }

    public function test_get_normalized_returns_uppercase_9_chars(): void
    {
        $idCard = $this->make('abc-123-454', 'ABC123454');

        $this->assertSame('ABC123454', $idCard->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $idCard = $this->make('ABC123454', 'ABC123454');

        $this->assertSame('ABC123454', (string) $idCard);
    }

    public function test_get_series_returns_first_three_letters(): void
    {
        $idCard = $this->make('ABC123454', 'ABC123454');

        $this->assertSame('ABC', $idCard->getSeries());
    }

    public function test_get_sequential_number_returns_five_digits(): void
    {
        $idCard = $this->make('ABC123454', 'ABC123454');

        $this->assertSame('12345', $idCard->getSequentialNumber());
    }

    public function test_get_check_digit_returns_last_character(): void
    {
        $idCard = $this->make('ABC123454', 'ABC123454');

        $this->assertSame('4', $idCard->getCheckDigit());
    }

    public function test_get_series_reflects_normalized_series(): void
    {
        $idCard = $this->make('XYZ987659', 'XYZ987659');

        $this->assertSame('XYZ', $idCard->getSeries());
    }

    public function test_get_sequential_number_with_zeros(): void
    {
        $idCard = $this->make('ZBA000008', 'ZBA000008');

        $this->assertSame('00000', $idCard->getSequentialNumber());
    }
}
