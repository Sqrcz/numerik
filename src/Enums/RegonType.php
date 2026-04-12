<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Enums;

enum RegonType: string
{
    case Individual = 'individual';
    case LegalEntity = 'legal_entity';

    public function label(): string
    {
        return match($this) {
            RegonType::Individual => 'Individual (9-digit)',
            RegonType::LegalEntity => 'Legal Entity (14-digit)',
        };
    }
}
