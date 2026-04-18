<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Enums\RegonType;
use SlashLab\Numerik\ValueObjects\Regon;

final class RegonTest extends TestCase
{
    public function test_get_raw_returns_original_input(): void
    {
        $regon = new Regon(raw: '850 518 457', normalized: '850518457', type: RegonType::Individual);

        $this->assertSame('850 518 457', $regon->getRaw());
    }

    public function test_get_normalized_returns_digits_only(): void
    {
        $regon = new Regon(raw: '850 518 457', normalized: '850518457', type: RegonType::Individual);

        $this->assertSame('850518457', $regon->getNormalized());
    }

    public function test_to_string_returns_normalized(): void
    {
        $regon = new Regon(raw: '850518457', normalized: '850518457', type: RegonType::Individual);

        $this->assertSame('850518457', (string) $regon);
    }

    public function test_get_type_returns_individual_for_9_digit(): void
    {
        $regon = new Regon(raw: '850518457', normalized: '850518457', type: RegonType::Individual);

        $this->assertSame(RegonType::Individual, $regon->getType());
    }

    public function test_get_type_returns_legal_entity_for_14_digit(): void
    {
        $regon = new Regon(raw: '85051845749370', normalized: '85051845749370', type: RegonType::LegalEntity);

        $this->assertSame(RegonType::LegalEntity, $regon->getType());
    }

    public function test_get_base_regon_returns_first_9_digits_for_individual(): void
    {
        $regon = new Regon(raw: '850518457', normalized: '850518457', type: RegonType::Individual);

        $this->assertSame('850518457', $regon->getBaseRegon());
    }

    public function test_get_base_regon_returns_first_9_digits_for_legal_entity(): void
    {
        $regon = new Regon(raw: '85051845749370', normalized: '85051845749370', type: RegonType::LegalEntity);

        $this->assertSame('850518457', $regon->getBaseRegon());
    }

    public function test_get_local_unit_suffix_returns_null_for_individual(): void
    {
        $regon = new Regon(raw: '850518457', normalized: '850518457', type: RegonType::Individual);

        $this->assertNull($regon->getLocalUnitSuffix());
    }

    public function test_get_local_unit_suffix_returns_last_5_digits_for_legal_entity(): void
    {
        $regon = new Regon(raw: '85051845749370', normalized: '85051845749370', type: RegonType::LegalEntity);

        $this->assertSame('49370', $regon->getLocalUnitSuffix());
    }

    public function test_is_local_unit_returns_false_for_individual(): void
    {
        $regon = new Regon(raw: '850518457', normalized: '850518457', type: RegonType::Individual);

        $this->assertFalse($regon->isLocalUnit());
    }

    public function test_is_local_unit_returns_true_for_legal_entity(): void
    {
        $regon = new Regon(raw: '85051845749370', normalized: '85051845749370', type: RegonType::LegalEntity);

        $this->assertTrue($regon->isLocalUnit());
    }
}
