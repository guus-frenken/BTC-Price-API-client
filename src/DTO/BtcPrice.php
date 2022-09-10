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

    public static function create(array $data): self
    {
        return new self(
            price: $data['price'],
            priceFormatted: $data['priceFormatted'],
            timestamp: $data['timestamp'],
        );
    }
}
