<?php

declare(strict_types=1);

namespace SlashLab\Numerik;

use SlashLab\Numerik\Identifiers\IbanIdentifier;
use SlashLab\Numerik\Identifiers\KrsIdentifier;
use SlashLab\Numerik\Identifiers\NipIdentifier;
use SlashLab\Numerik\Identifiers\NrbIdentifier;
use SlashLab\Numerik\Identifiers\PeselIdentifier;
use SlashLab\Numerik\Identifiers\RegonIdentifier;
use SlashLab\Numerik\Identifiers\VatEuIdentifier;

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

    public static function nrb(bool $strict = true): NrbIdentifier
    {
        return new NrbIdentifier(strict: $strict);
    }

    public static function vatEu(bool $strict = true): VatEuIdentifier
    {
        return new VatEuIdentifier(strict: $strict);
    }

    public static function iban(bool $strict = true): IbanIdentifier
    {
        return new IbanIdentifier(strict: $strict);
    }
}
