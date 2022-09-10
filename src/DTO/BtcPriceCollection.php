<?php

namespace App\DTO;

use ArrayIterator;
use IteratorAggregate;

class BtcPriceCollection implements IteratorAggregate
{
    private array $btcPrices;

    public function __construct(BtcPrice ...$btcPrices)
    {
        $this->btcPrices = $btcPrices;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->btcPrices);
    }
}
