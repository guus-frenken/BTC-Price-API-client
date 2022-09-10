<?php

namespace App\Config;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';

    public function getCurrencySymbol(): string
    {
        return match ($this) {
            self::EUR => '€',
            self::USD => '$',
        };
    }
}
