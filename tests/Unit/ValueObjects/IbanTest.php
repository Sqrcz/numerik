<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\Iban;

final class IbanTest extends TestCase
{
    private function make(string $raw, string $normalized): Iban
    {
        return new Iban(raw: $raw, normalized: $normalized);
    }

    public function test_get_raw_returns_original_input(): void
    {
        $iban = $this->make('PL61 1020 1026 0000 0000 0000 0000', 'PL61102010260000000000000000');

        $this->assertSame('PL61 1020 1026 0000 0000 0000 0000', $iban->getRaw());
    }

    public function test_get_normalized_returns_pl_prefixed_digits(): void
    {
        $iban = $this->make('PL61 1020 1026 0000 0000 0000 0000', 'PL61102010260000000000000000');

        $this->assertSame('PL61102010260000000000000000', $iban->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('PL61102010260000000000000000', (string) $iban);
    }

    public function test_get_formatted_returns_grouped_iban(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('PL61 1020 1026 0000 0000 0000 0000', $iban->getFormatted());
    }

    public function test_get_country_code_returns_pl(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('PL', $iban->getCountryCode());
    }

    public function test_get_nrb_returns_26_digit_string(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('61102010260000000000000000', $iban->getNrb());
    }

    public function test_get_check_digits_returns_two_digits(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('61', $iban->getCheckDigits());
    }

    public function test_get_sort_code_returns_eight_digits(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('10201026', $iban->getSortCode());
    }

    public function test_get_bank_code_returns_three_digits(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('102', $iban->getBankCode());
    }

    public function test_get_bank_code_varies_by_iban(): void
    {
        $iban = $this->make('PL19109020040000000000000000', 'PL19109020040000000000000000');

        $this->assertSame('109', $iban->getBankCode());
    }

    public function test_get_account_number_returns_sixteen_digits(): void
    {
        $iban = $this->make('PL61102010260000000000000000', 'PL61102010260000000000000000');

        $this->assertSame('0000000000000000', $iban->getAccountNumber());
    }

    public function test_get_account_number_returns_correct_digits_for_non_zero_account(): void
    {
        $iban = $this->make('PL54102010261234567890123456', 'PL54102010261234567890123456');

        $this->assertSame('1234567890123456', $iban->getAccountNumber());
    }
}
