<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;

final readonly class VatEu implements IdentifierInterface
{
    public function __construct(
        private string $raw,
        private string $normalized,
    ) {
    }

    #[\Override]
    public function getRaw(): string
    {
        return $this->raw;
    }

    #[\Override]
    public function getNormalized(): string
    {
        return $this->normalized;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->normalized;
    }

    public function getCountryCode(): string
    {
        return 'PL';
    }

    public function getNip(): string
    {
        return substr($this->normalized, 2);
    }

    public function getFormatted(): string
    {
        $nip = $this->getNip();

        return sprintf(
            '%s%s-%s-%s-%s',
            $this->getCountryCode(),
            substr($nip, 0, 3),
            substr($nip, 3, 3),
            substr($nip, 6, 2),
            substr($nip, 8, 2),
        );
    }
}
