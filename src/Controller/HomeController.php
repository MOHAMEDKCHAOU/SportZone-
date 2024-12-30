<?php

namespace App\Controller;

use App\Repository\AbonnementRepository;
use App\Repository\SalleDeSportRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SalleDeSportRepository $salleDeSportRepository,
        AbonnementRepository $abonnementRepository,
        ServiceRepository $serviceRepository
    ): Response {
        // Fetch all "salles" from the database
        $salles = $salleDeSportRepository->findAll();

        // Fetch all "services" from the database
        $services = $serviceRepository->findAll();

        // Count entities
        $countSalle = $salleDeSportRepository->count([]);
        $countAbonnement = $abonnementRepository->count([]);

        // Pass data to the template
        return $this->render('home/index.html.twig', [
            'salles' => $salles,
            'services' => $services,
            'countSalle' => $countSalle,
            'countAbonnement' => $countAbonnement,
        ]);
    }
}
