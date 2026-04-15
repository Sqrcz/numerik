<?php

declare(strict_types=1);

namespace SlashLab\Numerik;

use SlashLab\Numerik\Identifiers\KrsIdentifier;
use SlashLab\Numerik\Identifiers\NipIdentifier;
use SlashLab\Numerik\Identifiers\PeselIdentifier;
use SlashLab\Numerik\Identifiers\RegonIdentifier;

final class Numerik
{
    public static function pesel(bool $strict = true): PeselIdentifier
    {
        return new PeselIdentifier(strict: $strict);
    }

    public static function nip(bool $strict = true): NipIdentifier
    {
        return new NipIdentifier(strict: $strict);
    }

    public static function regon(bool $strict = true): RegonIdentifier
    {
        return new RegonIdentifier(strict: $strict);
    }

    public static function krs(bool $strict = true): KrsIdentifier
    {
        return new KrsIdentifier(strict: $strict);
    }
}
