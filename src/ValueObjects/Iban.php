<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;

final readonly class Iban implements IdentifierInterface
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
        return implode(' ', str_split($this->normalized, 4));
    }

    public function getCountryCode(): string
    {
        return 'PL';
    }

    public function getNrb(): string
    {
        return substr($this->normalized, 2);
    }

    public function getCheckDigits(): string
    {
        return substr($this->normalized, 2, 2);
    }

    public function getSortCode(): string
    {
        return substr($this->normalized, 4, 8);
    }

    public function getBankCode(): string
    {
        return substr($this->normalized, 4, 3);
    }

    public function getAccountNumber(): string
    {
        return substr($this->normalized, 12, 16);
    }
}
