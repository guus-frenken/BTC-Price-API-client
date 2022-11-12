<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/', name: 'default_')]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[Route('/{view}', name: 'vue', requirements: ['view' => '^(?!.*api/).+'])]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}
