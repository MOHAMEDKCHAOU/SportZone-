<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProprietaireHomeController extends AbstractController
{
    #[Route('/proprietaire/home', name: 'app_proprietaire_home')]
    public function index(): Response
    {
        return $this->render('proprietaire_home/index.html.twig', [
            'controller_name' => 'ProprietaireHomeController',
        ]);
    }
}
