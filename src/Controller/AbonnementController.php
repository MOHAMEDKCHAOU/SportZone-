<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\SalleDeSport;
use App\Form\AbonnementType;
use App\Repository\AbonnementRepository;
use App\Repository\SalleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\ProprietaireSalle;

#[Route('/proprietaire/abonnement')]
class AbonnementController extends AbstractController
{
    #[Route('/create', name: 'abonnement_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SalleDeSportRepository $salleDeSportRepository
    ): Response {
        $user = $this->getUser();

        // Ensure the user is a ProprietaireSalle
        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent créer un abonnement.');
        }

        // Fetch only the SalleDeSport that belong to the logged-in ProprietaireSalle
        $salles = $salleDeSportRepository->findBy(['proprietaire' => $user]);

        // Create a new Abonnement instance
        $abonnement = new Abonnement();

        // Create the form for the abonnement
        $form = $this->createForm(AbonnementType::class, $abonnement, [
            'salles' => $salles, // Pass filtered salles as options to the form
        ]);

        $form->handleRequest($request);

        // If the form is submitted and valid, proceed with creation
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the selected SalleDeSport from the form
            $salle = $form->get('salle')->getData();

            // Ensure the SalleDeSport is owned by the ProprietaireSalle
            if (!$salle || $salle->getProprietaire() !== $user) {
                throw new AccessDeniedException('Cette salle de sport ne vous appartient pas.');
            }

            // Set the salle for the abonnement
            $abonnement->setSalle($salle);

            // Do not set the client (it should remain null)
            $abonnement->setClient(null); // Ensures the client is null during creation

            // Persist the new Abonnement entity
            $entityManager->persist($abonnement);
            $entityManager->flush();

            // Redirect after successful creation to the abonnement list
            return $this->redirectToRoute('abonnement_list');
        }

        // Render the form in the template
        return $this->render('abonnement/creer_abonnement.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'abonnement_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Abonnement $abonnement,
        EntityManagerInterface $entityManager,
        SalleDeSportRepository $salleDeSportRepository
    ): Response {
        $user = $this->getUser();

        // Ensure the user is a ProprietaireSalle and owns the abonnement
        if (!$user instanceof ProprietaireSalle || $abonnement->getSalle()->getProprietaire() !== $user) {
            throw new AccessDeniedException('Accès refusé.');
        }

        // Fetch only the SalleDeSport that belong to the logged-in ProprietaireSalle
        $salles = $salleDeSportRepository->findBy(['proprietaire' => $user]);

        $form = $this->createForm(AbonnementType::class, $abonnement, [
            'salles' => $salles, // Pass filtered salles as options to the form
        ]);

        $form->handleRequest($request);

        // If the form is submitted and valid, update the abonnement
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Redirect after successful edit to the abonnement list
            return $this->redirectToRoute('abonnement_list');
        }

        return $this->render('abonnement/edit.html.twig', [
            'form' => $form->createView(),
            'abonnement' => $abonnement,
        ]);
    }

    #[Route('/delete/{id}', name: 'abonnement_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Abonnement $abonnement,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        // Ensure the user is a ProprietaireSalle and owns the abonnement
        if (!$user instanceof ProprietaireSalle || $abonnement->getSalle()->getProprietaire() !== $user) {
            throw new AccessDeniedException('Accès refusé.');
        }

        // Validate the CSRF token
        if ($this->isCsrfTokenValid('delete' . $abonnement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($abonnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('abonnement_list');
    }
    #[Route('/all', name: 'abonnement_list_all', methods: ['GET'])]
    public function listAllAbonnements(Request $request, AbonnementRepository $abonnementRepository, EntityManagerInterface $entityManager): Response
    {
        // Get the search query from the request
        $serviceName = $request->query->get('service_name', '');

        // Fetch abonnements based on the service name if a search query is provided
        if (!empty($serviceName)) {
            $queryBuilder = $entityManager->getRepository(Abonnement::class)->createQueryBuilder('a');
            $queryBuilder
                ->leftJoin('a.services', 's') // Join the services relation
                ->where('s.nom LIKE :serviceName') // Filter by service name
                ->setParameter('serviceName', '%' . $serviceName . '%');
            $abonnements = $queryBuilder->getQuery()->getResult();
        } else {
            // Fetch all abonnements if no search query is provided
            $abonnements = $abonnementRepository->findAll();
        }

        // Render the list in the template
        return $this->render('abonnement/list_all_abonnement.html.twig', [
            'abonnements' => $abonnements,
            'service_name' => $serviceName, // Pass the search query back for the form
        ]);
    }
    #[Route('/', name: 'abonnement_list', methods: ['GET'])]
    public function list(AbonnementRepository $abonnementRepository): Response
    {
        $user = $this->getUser();

        // Ensure the user is a ProprietaireSalle
        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.');
        }

        // Fetch abonnements for all the salles that belong to the logged-in ProprietaireSalle
        $abonnements = $abonnementRepository->createQueryBuilder('a')
            ->innerJoin('a.salle', 's')
            ->where('s.proprietaire = :proprietaire')
            ->setParameter('proprietaire', $user)
            ->getQuery()
            ->getResult();

        return $this->render('abonnement/list_abonnement.html.twig', [
            'abonnements' => $abonnements,
        ]);
    }
}
