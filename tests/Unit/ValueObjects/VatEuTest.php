<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\VatEu;

final class VatEuTest extends TestCase
{
    private function make(string $raw, string $normalized): VatEu
    {
        return new VatEu(raw: $raw, normalized: $normalized);
    }

    public function test_get_raw_returns_original_input(): void
    {
        $vatEu = $this->make('PL526-025-02-74', 'PL5260250274');

        $this->assertSame('PL526-025-02-74', $vatEu->getRaw());
    }

    public function test_get_normalized_returns_pl_prefixed_digits(): void
    {
        $vatEu = $this->make('PL526-025-02-74', 'PL5260250274');

        $this->assertSame('PL5260250274', $vatEu->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $vatEu = $this->make('PL5260250274', 'PL5260250274');

        $this->assertSame('PL5260250274', (string) $vatEu);
    }

    public function test_get_country_code_returns_pl(): void
    {
        $vatEu = $this->make('PL5260250274', 'PL5260250274');

        $this->assertSame('PL', $vatEu->getCountryCode());
    }

    public function test_get_nip_returns_ten_digit_string(): void
    {
        $vatEu = $this->make('PL5260250274', 'PL5260250274');

        $this->assertSame('5260250274', $vatEu->getNip());
    }

    public function test_get_nip_varies_by_input(): void
    {
        $vatEu = $this->make('PL1002345672', 'PL1002345672');

        $this->assertSame('1002345672', $vatEu->getNip());
    }

    public function test_get_formatted_returns_pl_prefixed_nip_format(): void
    {
        $vatEu = $this->make('PL5260250274', 'PL5260250274');

        $this->assertSame('PL526-025-02-74', $vatEu->getFormatted());
    }
}
