<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\Passport;

final class PassportTest extends TestCase
{
    private function make(string $raw, string $normalized): Passport
    {
        return new Passport(raw: $raw, normalized: $normalized);
    }

    public function test_get_raw_returns_original_input(): void
    {
        $passport = $this->make('ab 123456 4', 'AB1234564');

        $this->assertSame('ab 123456 4', $passport->getRaw());
    }

    public function test_get_normalized_returns_uppercase_9_chars(): void
    {
        $passport = $this->make('ab 123456 4', 'AB1234564');

        $this->assertSame('AB1234564', $passport->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $passport = $this->make('AB1234564', 'AB1234564');

        $this->assertSame('AB1234564', (string) $passport);
    }

    public function test_get_series_returns_first_two_letters(): void
    {
        $passport = $this->make('AB1234564', 'AB1234564');

        $this->assertSame('AB', $passport->getSeries());
    }

    public function test_get_sequential_number_returns_six_digits(): void
    {
        $passport = $this->make('AB1234564', 'AB1234564');

        $this->assertSame('123456', $passport->getSequentialNumber());
    }

    public function test_get_check_digit_returns_last_character(): void
    {
        $passport = $this->make('AB1234564', 'AB1234564');

        $this->assertSame('4', $passport->getCheckDigit());
    }

    public function test_get_series_reflects_normalized_series(): void
    {
        $passport = $this->make('ZZ1234561', 'ZZ1234561');

        $this->assertSame('ZZ', $passport->getSeries());
    }

    public function test_get_sequential_number_with_zeros(): void
    {
        $passport = $this->make('AA0000000', 'AA0000000');

        $this->assertSame('000000', $passport->getSequentialNumber());
    }
}
