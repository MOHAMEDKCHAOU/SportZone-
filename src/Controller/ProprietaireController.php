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

#[Route('/proprietaire')]
class ProprietaireController extends AbstractController
{
#[Route('/salles', name: 'proprietaire_salles')]
public function listSalles(SalleDeSportRepository $salleRepo): Response
{
$user = $this->getUser(); // Get the logged-in ProprietaireSalle
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
$salle = new SalleDeSport();
$form = $this->createForm(SalleDeSportType::class, $salle);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
$salle->setProprietaire($this->getUser()); // Set the owner as the current user
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
if ($salle->getProprietaire() !== $this->getUser()) {
throw $this->createAccessDeniedException('Cette salle ne vous appartient pas.');
}

$entityManager->remove($salle);
$entityManager->flush();

return $this->redirectToRoute('proprietaire_salles');
}
}
