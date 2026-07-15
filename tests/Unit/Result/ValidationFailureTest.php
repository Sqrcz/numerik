<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Result;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Exceptions\InvalidChecksumException;
use SlashLab\Numerik\Exceptions\InvalidDateException;
use SlashLab\Numerik\Exceptions\InvalidFormatException;
use SlashLab\Numerik\Result\ValidationFailure;

final class ValidationFailureTest extends TestCase
{
    public function test_to_exception_maps_invalid_checksum(): void
    {
        $failure   = new ValidationFailure(ValidationFailureReason::InvalidChecksum, 'Bad checksum.');
        $exception = $failure->toException();

        $this->assertInstanceOf(InvalidChecksumException::class, $exception);
        $this->assertSame('Bad checksum.', $exception->getMessage());
    }

    public function test_to_exception_maps_invalid_date(): void
    {
        $failure   = new ValidationFailure(ValidationFailureReason::InvalidDate, 'Not a real date.');
        $exception = $failure->toException();

        $this->assertInstanceOf(InvalidDateException::class, $exception);
        $this->assertSame('Not a real date.', $exception->getMessage());
    }

    public function test_to_exception_maps_future_date(): void
    {
        $failure   = new ValidationFailure(ValidationFailureReason::FutureDate, 'Date is in the future.');
        $exception = $failure->toException();

        $this->assertInstanceOf(InvalidDateException::class, $exception);
        $this->assertSame('Date is in the future.', $exception->getMessage());
    }

    public function test_to_exception_maps_invalid_month(): void
    {
        $failure   = new ValidationFailure(ValidationFailureReason::InvalidMonth, 'Unknown month encoding.');
        $exception = $failure->toException();

        $this->assertInstanceOf(InvalidDateException::class, $exception);
        $this->assertSame('Unknown month encoding.', $exception->getMessage());
    }

    public function test_to_exception_maps_default_reason(): void
    {
        $failure   = new ValidationFailure(ValidationFailureReason::InvalidLength, 'Wrong length.');
        $exception = $failure->toException();

        $this->assertInstanceOf(InvalidFormatException::class, $exception);
        $this->assertSame('Wrong length.', $exception->getMessage());
    }
}
