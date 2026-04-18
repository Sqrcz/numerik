<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\RegonType;

final class RegonTypeTest extends TestCase
{
    public function test_individual_label(): void
    {
        $this->assertSame('Individual (9-digit)', RegonType::Individual->label());
    }

    public function test_legal_entity_label(): void
    {
        $this->assertSame('Legal Entity (14-digit)', RegonType::LegalEntity->label());
    }
}
