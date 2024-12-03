<?php

namespace App\Controller;

use App\Repository\SalleDeSportRepository;
use App\Repository\SalleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SalleDeSportRepository $salleDeSportRepository): Response
    {
        // Fetch all "salles" from the database
        $salles = $salleDeSportRepository->findAll();

        // Pass "salles" to the template
        return $this->render('home/index.html.twig', [
            'salles' => $salles,
        ]);
    }
}
