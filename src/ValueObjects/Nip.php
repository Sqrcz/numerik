<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;

final readonly class Nip implements IdentifierInterface
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

    public function getFormatted(): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            substr($this->normalized, 0, 3),
            substr($this->normalized, 3, 3),
            substr($this->normalized, 6, 2),
            substr($this->normalized, 8, 2),
        );
    }

    public function getFormattedAlternative(): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            substr($this->normalized, 0, 3),
            substr($this->normalized, 3, 2),
            substr($this->normalized, 5, 2),
            substr($this->normalized, 7, 3),
        );
    }

    public function getTaxOfficeCode(): string
    {
        return substr($this->normalized, 0, 3);
    }
}
