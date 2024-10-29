<?php

namespace App\Controller;

use App\Entity\SalleDeSport;
use App\Form\SalleDeSportType;
use App\Repository\SalleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\ProprietaireSalle; // Make sure to import your ProprietaireSalle entity

#[Route('/proprietaire')]
class ProprietaireController extends AbstractController
{
    #[Route('/salles', name: 'proprietaire_salles')]
    public function listSalles(SalleDeSportRepository $salleRepo): Response
    {
        $user = $this->getUser(); // Get the logged-in user

        // Check if the user is an instance of ProprietaireSalle
        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.'); // Message displayed if access is denied
        }

        // Fetch salles associated with the ProprietaireSalle
        $salles = $salleRepo->findBy(['proprietaire' => $user]);

        return $this->render('proprietaire/salles.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/salle/creer', name: 'proprietaire_salle_creer')]
    public function createSalle(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser(); // Get the logged-in user

        // Check if the user is an instance of ProprietaireSalle
        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.'); // Message displayed if access is denied
        }

        $salle = new SalleDeSport();
        $form = $this->createForm(SalleDeSportType::class, $salle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle->setProprietaire($user); // Set the owner as the current user
            $entityManager->persist($salle);
            $entityManager->flush();

            return $this->redirectToRoute('proprietaire_salles');
        }

        return $this->render('proprietaire/creer_salle.html.twig', [
            'SalleDeSportType' => $form->createView(),
        ]);
    }

    #[Route('/salle/{id}/supprimer', name: 'proprietaire_salle_supprimer')]
    public function deleteSalle(
        SalleDeSport $salle,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser(); // Get the logged-in user

        // Check if the user is an instance of ProprietaireSalle
        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.'); // Message displayed if access is denied
        }

        if ($salle->getProprietaire() !== $user) {
            throw $this->createAccessDeniedException('Cette salle ne vous appartient pas.');
        }

        $entityManager->remove($salle);
        $entityManager->flush();

        return $this->redirectToRoute('proprietaire_salles');
    }
}
