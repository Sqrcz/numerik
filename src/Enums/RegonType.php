<?php

declare(strict_types=1);

namespace SlashLab\Numerik\Enums;

enum RegonType: string
{
    case Individual   = 'individual';    // 9-digit
    case LegalEntity  = 'legal_entity';  // 14-digit
}
