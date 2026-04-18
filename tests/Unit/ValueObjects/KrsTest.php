<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\ValueObjects\Krs;

final class KrsTest extends TestCase
{
    public function test_get_raw_returns_original_input(): void
    {
        $krs = new Krs(raw: '127206', normalized: '127206');

        $this->assertSame('127206', $krs->getRaw());
    }

    public function test_get_normalized_returns_stripped_value(): void
    {
        $krs = new Krs(raw: '0000127206', normalized: '0000127206');

        $this->assertSame('0000127206', $krs->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $krs = new Krs(raw: '0000127206', normalized: '0000127206');

        $this->assertSame('0000127206', (string) $krs);
    }

    public function test_get_formatted_pads_to_10_digits(): void
    {
        $krs = new Krs(raw: '127206', normalized: '127206');

        $this->assertSame('0000127206', $krs->getFormatted());
    }

    public function test_get_formatted_returns_10_digits_unchanged_when_already_full(): void
    {
        $krs = new Krs(raw: '0000127206', normalized: '0000127206');

        $this->assertSame('0000127206', $krs->getFormatted());
    }

    public function test_get_numeric_value_returns_integer(): void
    {
        $krs = new Krs(raw: '0000127206', normalized: '0000127206');

        $this->assertSame(127206, $krs->getNumericValue());
    }

    public function test_get_numeric_value_for_short_form(): void
    {
        $krs = new Krs(raw: '127206', normalized: '127206');

        $this->assertSame(127206, $krs->getNumericValue());
    }
}
