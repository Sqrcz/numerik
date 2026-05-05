<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Identifiers\IbanIdentifier;
use SlashLab\Numerik\Identifiers\KrsIdentifier;
use SlashLab\Numerik\Identifiers\NipIdentifier;
use SlashLab\Numerik\Identifiers\NrbIdentifier;
use SlashLab\Numerik\Identifiers\PeselIdentifier;
use SlashLab\Numerik\Identifiers\RegonIdentifier;
use SlashLab\Numerik\Identifiers\VatEuIdentifier;
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

    public function test_nrb_returns_nrb_identifier(): void
    {
        $this->assertInstanceOf(NrbIdentifier::class, Numerik::nrb());
    }

    public function test_vat_eu_returns_vat_eu_identifier(): void
    {
        $this->assertInstanceOf(VatEuIdentifier::class, Numerik::vatEu());
    }

    public function test_iban_returns_iban_identifier(): void
    {
        $this->assertInstanceOf(IbanIdentifier::class, Numerik::iban());
    }

    public function test_pesel_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::pesel()->isStrict());
    }

    public function test_nip_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::nip()->isStrict());
    }

    public function test_regon_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::regon()->isStrict());
    }

    public function test_krs_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::krs()->isStrict());
    }

    public function test_nrb_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::nrb()->isStrict());
    }

    public function test_vat_eu_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::vatEu()->isStrict());
    }

    public function test_iban_strict_mode_defaults_to_true(): void
    {
        $this->assertTrue(Numerik::iban()->isStrict());
    }

    public function test_pesel_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::pesel(strict: false)->isStrict());
    }

    public function test_nip_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::nip(strict: false)->isStrict());
    }

    public function test_regon_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::regon(strict: false)->isStrict());
    }

    public function test_krs_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::krs(strict: false)->isStrict());
    }

    public function test_nrb_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::nrb(strict: false)->isStrict());
    }

    public function test_vat_eu_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::vatEu(strict: false)->isStrict());
    }

    public function test_iban_strict_mode_can_be_disabled(): void
    {
        $this->assertFalse(Numerik::iban(strict: false)->isStrict());
    }
}
