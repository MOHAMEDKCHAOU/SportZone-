<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceEntityController extends AbstractController
{
    #[Route('/service/create', name: 'create_service', methods: ['GET', 'POST'])]
    public function createService(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash('success', 'Le service a été créé avec succès.');

            return $this->redirectToRoute('list_services');
        }

        return $this->render('service/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/service/list', name: 'list_services', methods: ['GET'])]
    public function listServices(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        return $this->render('service/list.html.twig', [
            'services' => $services,
        ]);
    }
}
