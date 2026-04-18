<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Result;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\ValidationFailureReason;
use SlashLab\Numerik\Result\ValidationFailure;
use SlashLab\Numerik\Result\ValidationResult;

final class ValidationResultTest extends TestCase
{
    public function test_pass_creates_valid_result(): void
    {
        $result = ValidationResult::pass();

        $this->assertTrue($result->isValid);
        $this->assertEmpty($result->failures);
    }

    public function test_fail_creates_invalid_result_with_failures(): void
    {
        $failure = new ValidationFailure(ValidationFailureReason::InvalidLength, 'Too short.');
        $result  = ValidationResult::fail([$failure]);

        $this->assertFalse($result->isValid);
        $this->assertCount(1, $result->failures);
        $this->assertSame($failure, $result->failures[0]);
    }

    public function test_fail_with_reason_creates_single_failure(): void
    {
        $result = ValidationResult::failWithReason(ValidationFailureReason::InvalidChecksum, 'Bad checksum.');

        $this->assertFalse($result->isValid);
        $this->assertCount(1, $result->failures);
        $this->assertSame(ValidationFailureReason::InvalidChecksum, $result->failures[0]->reason);
    }

    public function test_is_failed_returns_true_for_failed_result(): void
    {
        $result = ValidationResult::failWithReason(ValidationFailureReason::InvalidLength, 'msg');

        $this->assertTrue($result->isFailed());
    }

    public function test_is_failed_returns_false_for_passing_result(): void
    {
        $this->assertFalse(ValidationResult::pass()->isFailed());
    }

    public function test_get_first_failure_returns_null_when_no_failures(): void
    {
        $this->assertNull(ValidationResult::pass()->getFirstFailure());
    }

    public function test_get_first_failure_returns_first_entry(): void
    {
        $first  = new ValidationFailure(ValidationFailureReason::InvalidLength, 'First.');
        $second = new ValidationFailure(ValidationFailureReason::InvalidChecksum, 'Second.');
        $result = ValidationResult::fail([$first, $second]);

        $this->assertSame($first, $result->getFirstFailure());
    }

    public function test_has_failure_reason_returns_false_when_reason_not_present(): void
    {
        $result = ValidationResult::failWithReason(ValidationFailureReason::InvalidLength, 'msg');

        $this->assertFalse($result->hasFailureReason(ValidationFailureReason::InvalidChecksum));
    }

    public function test_has_failure_reason_returns_true_when_reason_present(): void
    {
        $result = ValidationResult::failWithReason(ValidationFailureReason::InvalidChecksum, 'msg');

        $this->assertTrue($result->hasFailureReason(ValidationFailureReason::InvalidChecksum));
    }
}
