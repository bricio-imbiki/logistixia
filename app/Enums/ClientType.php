<?php

namespace App\Enums;

enum ClientType: string
{
    case industriel = 'industriel';
    case commercial = 'commercial';
    case particulier = 'particulier';

    public function label(): string
    {
        return match($this) {
            self::industriel => 'Industriel',
            self::commercial => 'Commercial',
            self::particulier => 'Particulier',
        };
    }
}
