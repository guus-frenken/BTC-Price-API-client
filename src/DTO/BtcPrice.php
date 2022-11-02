<?php

namespace App\DTO;

class BtcPrice
{
    public function __construct(
        public readonly float $price,
        public readonly string $priceFormatted,
        public readonly int $timestamp,
    ) {
    }
}
