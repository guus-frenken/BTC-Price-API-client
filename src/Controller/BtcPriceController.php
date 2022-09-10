<?php

namespace App\Controller;

use App\Config\Currency;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BtcPrice\BtcPriceProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/btcprice', name: 'btc_price_')]
class BtcPriceController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(BtcPriceProviderInterface $btcPriceProvider): Response
    {
        $price = $btcPriceProvider->getPrice(Currency::EUR);

        return $this->json(['price' => $price]);
    }

    #[Route('/history/', name: 'history')]
    public function history(BtcPriceProviderInterface $btcPriceProvider): Response
    {
        $history = $btcPriceProvider->get30DayPriceHistory(Currency::EUR);

        return $this->json(['prices' => $history]);
    }
}
