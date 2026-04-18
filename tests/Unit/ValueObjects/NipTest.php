<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\Nip;

final class NipTest extends TestCase
{
    private function make(string $raw, string $normalized): Nip
    {
        return new Nip(raw: $raw, normalized: $normalized);
    }

    public function test_get_raw_returns_original_input(): void
    {
        $nip = $this->make('526-025-02-74', '5260250274');

        $this->assertSame('526-025-02-74', $nip->getRaw());
    }

    public function test_get_normalized_returns_digits_only(): void
    {
        $nip = $this->make('526-025-02-74', '5260250274');

        $this->assertSame('5260250274', $nip->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $nip = $this->make('526-025-02-74', '5260250274');

        $this->assertSame('5260250274', (string) $nip);
    }

    public function test_get_formatted_returns_standard_format(): void
    {
        $nip = $this->make('5260250274', '5260250274');

        $this->assertSame('526-025-02-74', $nip->getFormatted());
    }

    public function test_get_formatted_alternative_returns_alternative_format(): void
    {
        $nip = $this->make('5260250274', '5260250274');

        $this->assertSame('526-02-50-274', $nip->getFormattedAlternative());
    }

    public function test_get_tax_office_code_returns_first_three_digits(): void
    {
        $nip = $this->make('5260250274', '5260250274');

        $this->assertSame('526', $nip->getTaxOfficeCode());
    }

    public function test_get_tax_office_code_varies_by_nip(): void
    {
        $nip = $this->make('1002345672', '1002345672');

        $this->assertSame('100', $nip->getTaxOfficeCode());
    }
}
