<?php

namespace App\Controller\Api;

use App\Config\Currency;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/currency', name: 'api_currency_')]
class CurrencyController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $currencies = array_map(fn(Currency $currency) => [
            'value' => $currency->value,
            'label' => $currency->name,
        ], Currency::cases());

        return $this->json(['currencies' => $currencies]);
    }
}
