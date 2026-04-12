<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SlashLab\Numerik\Numerik;
use SlashLab\Numerik\Validators\KrsValidator;
use SlashLab\Numerik\Validators\NipValidator;
use SlashLab\Numerik\Validators\PeselValidator;
use SlashLab\Numerik\Validators\RegonValidator;

final class NumerikTest extends TestCase
{
    public function test_pesel_returns_correct_validator(): void
    {
        $this->assertInstanceOf(PeselValidator::class, Numerik::pesel());
    }

    public function test_nip_returns_correct_validator(): void
    {
        $this->assertInstanceOf(NipValidator::class, Numerik::nip());
    }

    public function test_regon_returns_correct_validator(): void
    {
        $this->assertInstanceOf(RegonValidator::class, Numerik::regon());
    }

    public function test_krs_returns_correct_validator(): void
    {
        $this->assertInstanceOf(KrsValidator::class, Numerik::krs());
    }
}
