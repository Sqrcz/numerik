<?php

declare(strict_types=1);

namespace SlashLab\Numerik;

use SlashLab\Numerik\Validators\KrsValidator;
use SlashLab\Numerik\Validators\NipValidator;
use SlashLab\Numerik\Validators\PeselValidator;
use SlashLab\Numerik\Validators\RegonValidator;

final class Numerik
{
    public static function pesel(): PeselValidator
    {
        return new PeselValidator();
    }

    public static function nip(): NipValidator
    {
        return new NipValidator();
    }

    public static function regon(): RegonValidator
    {
        return new RegonValidator();
    }

    public static function krs(): KrsValidator
    {
        return new KrsValidator();
    }
}
