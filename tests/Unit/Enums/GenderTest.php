<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\Gender;

final class GenderTest extends TestCase
{
    public function test_male_label(): void
    {
        $this->assertSame('Male', Gender::Male->label());
    }

    public function test_female_label(): void
    {
        $this->assertSame('Female', Gender::Female->label());
    }
}
