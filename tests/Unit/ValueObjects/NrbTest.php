<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\Nrb;

final class NrbTest extends TestCase
{
    private function make(string $raw, string $normalized): Nrb
    {
        return new Nrb(raw: $raw, normalized: $normalized);
    }

    public function test_get_raw_returns_original_input(): void
    {
        $nrb = $this->make('61 1020 1026 0000 0000 0000 0000', '61102010260000000000000000');

        $this->assertSame('61 1020 1026 0000 0000 0000 0000', $nrb->getRaw());
    }

    public function test_get_normalized_returns_digits_only(): void
    {
        $nrb = $this->make('61 1020 1026 0000 0000 0000 0000', '61102010260000000000000000');

        $this->assertSame('61102010260000000000000000', $nrb->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('61102010260000000000000000', (string) $nrb);
    }

    public function test_get_formatted_returns_standard_polish_format(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('61 1020 1026 0000 0000 0000 0000', $nrb->getFormatted());
    }

    public function test_get_iban_returns_pl_prefixed_number(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('PL61102010260000000000000000', $nrb->getIban());
    }

    public function test_get_formatted_iban_returns_grouped_iban(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('PL61 1020 1026 0000 0000 0000 0000', $nrb->getFormattedIban());
    }

    public function test_get_check_digits_returns_first_two_digits(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('61', $nrb->getCheckDigits());
    }

    public function test_get_sort_code_returns_eight_digit_bank_code(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('10201026', $nrb->getSortCode());
    }

    public function test_get_bank_code_returns_first_three_digits_of_sort_code(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('102', $nrb->getBankCode());
    }

    public function test_get_bank_code_varies_by_nrb(): void
    {
        $nrb = $this->make('19109020040000000000000000', '19109020040000000000000000');

        $this->assertSame('109', $nrb->getBankCode());
    }

    public function test_get_account_number_returns_last_sixteen_digits(): void
    {
        $nrb = $this->make('61102010260000000000000000', '61102010260000000000000000');

        $this->assertSame('0000000000000000', $nrb->getAccountNumber());
    }

    public function test_get_account_number_returns_correct_digits_for_non_zero_account(): void
    {
        $nrb = $this->make('54102010261234567890123456', '54102010261234567890123456');

        $this->assertSame('1234567890123456', $nrb->getAccountNumber());
    }
}
