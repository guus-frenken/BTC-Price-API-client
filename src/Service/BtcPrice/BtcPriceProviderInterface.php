<?php

namespace App\Service\BtcPrice;

use App\DTO\BtcPrice;
use App\Config\Currency;
use App\DTO\BtcPriceCollection;

interface BtcPriceProviderInterface
{
    /**
     * Get the current BTC price in a given Currency
     */
    public function getPrice(Currency $currency): ?BtcPrice;

    /**
     * Get a 30 day BTC price history in a given Currency
     */
    public function get30DayPriceHistory(Currency $currency): BtcPriceCollection;
}
