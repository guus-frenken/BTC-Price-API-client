<?php

namespace App\Service;

use App\Config\Currency;
use Symfony\Contracts\Translation\TranslatorInterface;

class NumberFormatter
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * Format a number based on the active locale
     */
    public function format(int|float $number, int $decimals = 2, string $prefix = null, string $suffix = null): string
    {
        $separators = match ($this->translator->getLocale()) {
            'nl' => [',', '.'],
            default => ['.', ','],
        };

        $output = '';

        if (!is_null($prefix)) {
            $output .= $prefix;
        }

        $output .= number_format($number, $decimals, ...$separators);

        if (!is_null($suffix)) {
            $output .= $suffix;
        }

        return $output;
    }

    /**
     * Format a currency amount
     */
    public function formatCurrency(
        int|float|null $number,
        int $decimals = 2,
        Currency $currency = Currency::EUR,
    ): string {
        return $this->format($number, $decimals, prefix: $currency->getCurrencySymbol());
    }
}