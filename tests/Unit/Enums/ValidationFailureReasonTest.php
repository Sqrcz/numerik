<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;

final class ValidationFailureReasonTest extends TestCase
{
    public function test_invalid_length_label(): void
    {
        $this->assertSame('Invalid length', ValidationFailureReason::InvalidLength->label());
    }

    public function test_invalid_characters_label(): void
    {
        $this->assertSame('Invalid characters', ValidationFailureReason::InvalidCharacters->label());
    }

    public function test_invalid_format_label(): void
    {
        $this->assertSame('Invalid format', ValidationFailureReason::InvalidFormat->label());
    }

    public function test_invalid_checksum_label(): void
    {
        $this->assertSame('Invalid checksum', ValidationFailureReason::InvalidChecksum->label());
    }

    public function test_invalid_date_label(): void
    {
        $this->assertSame('Invalid date', ValidationFailureReason::InvalidDate->label());
    }

    public function test_future_date_label(): void
    {
        $this->assertSame('Future date', ValidationFailureReason::FutureDate->label());
    }

    public function test_invalid_month_label(): void
    {
        $this->assertSame('Invalid month', ValidationFailureReason::InvalidMonth->label());
    }

    public function test_all_zeros_label(): void
    {
        $this->assertSame('All zeros', ValidationFailureReason::AllZeros->label());
    }

    public function test_all_same_digit_label(): void
    {
        $this->assertSame('All same digit', ValidationFailureReason::AllSameDigit->label());
    }
}
