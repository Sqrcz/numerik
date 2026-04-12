<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use SlashLab\Numerik\Contracts\IdentifierInterface;
use SlashLab\Numerik\Enums\RegonType;

final readonly class Regon implements IdentifierInterface
{
    public function __construct(
        private string $raw,
        private string $normalized,
        private RegonType $type,
    ) {
    }

    public function getRaw(): string
    {
        return $this->raw;
    }

    public function getNormalized(): string
    {
        return $this->normalized;
    }

    public function __toString(): string
    {
        return $this->normalized;
    }

    public function getType(): RegonType
    {
        return $this->type;
    }

    public function getBaseRegon(): string
    {
        return substr($this->normalized, 0, 9);
    }

    public function getLocalUnitSuffix(): ?string
    {
        if ($this->type === RegonType::Individual) {
            return null;
        }

        return substr($this->normalized, 9, 5);
    }

    public function isLocalUnit(): bool
    {
        return $this->type === RegonType::LegalEntity;
    }
}
