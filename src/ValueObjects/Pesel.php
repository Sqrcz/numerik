<?php

declare(strict_types=1);

namespace SlashLab\Numerik\ValueObjects;

use DateTimeImmutable;
use SlashLab\Numerik\Contracts\IdentifierInterface;
use SlashLab\Numerik\Enums\Gender;

final readonly class Pesel implements IdentifierInterface
{
    public function __construct(
        private string $raw,
        private string $normalized,
        private DateTimeImmutable $birthDate,
        private Gender $gender,
        private int $ordinalNumber,
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

    public function getBirthDate(): DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function getOrdinalNumber(): int
    {
        return $this->ordinalNumber;
    }

    public function isMale(): bool
    {
        return $this->gender === Gender::Male;
    }

    public function isFemale(): bool
    {
        return $this->gender === Gender::Female;
    }

    public function getAge(): int
    {
        return $this->birthDate
            ->diff(new DateTimeImmutable('today'))
            ->y;
    }

    public function isAdult(): bool
    {
        return $this->getAge() >= 18;
    }

    public function getCentury(): int
    {
        return (int) $this->birthDate->format('Y') - ((int) $this->birthDate->format('Y') % 100);
    }
}
