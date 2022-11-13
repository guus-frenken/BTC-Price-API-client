<?php

namespace App\Controller\Api;

use App\Config\Currency;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BtcPrice\BtcPriceProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/btcprice', name: 'api_btcprice_')]
class BtcPriceController extends AbstractController
{
    public function __construct(private readonly BtcPriceProviderInterface $btcPriceProvider)
    {
    }

    #[Route('/{currency}', name: 'index')]
    public function index(string $currency): Response
    {
        $currency = Currency::tryFrom(strtoupper($currency));

        if (is_null($currency)) {
            return $this->json(['error' => 'Invalid currency'], 400);
        }

        $price = $this->btcPriceProvider->getPrice($currency);

        if (is_null($price)) {
            return $this->json(['error' => 'Could not get price'], 500);
        }

        return $this->json(['price' => $price]);
    }

    #[Route('/{currency}/history', name: 'history')]
    public function history(string $currency): Response
    {
        $currency = Currency::tryFrom(strtoupper($currency));

        if (is_null($currency)) {
            return $this->json(['error' => 'Invalid currency'], 400);
        }

        $history = $this->btcPriceProvider->get30DayPriceHistory($currency);

        if (empty($history)) {
            return $this->json(['error' => 'Could not get history'], 500);
        }

        return $this->json(['prices' => $history]);
    }
}
