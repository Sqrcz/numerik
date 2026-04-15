<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Identifiers\KrsIdentifier;
use SlashLab\Numerik\Identifiers\NipIdentifier;
use SlashLab\Numerik\Identifiers\PeselIdentifier;
use SlashLab\Numerik\Identifiers\RegonIdentifier;
use SlashLab\Numerik\Numerik;

final class NumerikTest extends TestCase
{
    public function test_pesel_returns_pesel_identifier(): void
    {
        $this->assertInstanceOf(PeselIdentifier::class, Numerik::pesel());
    }

    public function test_nip_returns_nip_identifier(): void
    {
        $this->assertInstanceOf(NipIdentifier::class, Numerik::nip());
    }

    public function test_regon_returns_regon_identifier(): void
    {
        $this->assertInstanceOf(RegonIdentifier::class, Numerik::regon());
    }

    public function test_krs_returns_krs_identifier(): void
    {
        $this->assertInstanceOf(KrsIdentifier::class, Numerik::krs());
    }

    public function test_strict_mode_defaults_to_true(): void
    {
        $this->assertInstanceOf(PeselIdentifier::class, Numerik::pesel());
        $this->assertInstanceOf(NipIdentifier::class, Numerik::nip());
        $this->assertInstanceOf(RegonIdentifier::class, Numerik::regon());
        $this->assertInstanceOf(KrsIdentifier::class, Numerik::krs());
    }

    public function test_strict_mode_can_be_disabled(): void
    {
        $this->assertInstanceOf(PeselIdentifier::class, Numerik::pesel(strict: false));
        $this->assertInstanceOf(NipIdentifier::class, Numerik::nip(strict: false));
        $this->assertInstanceOf(RegonIdentifier::class, Numerik::regon(strict: false));
        $this->assertInstanceOf(KrsIdentifier::class, Numerik::krs(strict: false));
    }
}
