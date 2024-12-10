<?php
namespace App\Controller;

use App\Entity\SalleDeSport;
use App\Entity\Abonnement;
use App\Form\SalleDeSportType;
use App\Repository\SalleDeSportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\ProprietaireSalle;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/proprietaire')]
class ProprietaireController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/salles', name: 'proprietaire_salles')]
    public function listSalles(SalleDeSportRepository $salleRepo): Response
    {
        $user = $this->getUser();

        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.');
        }

        $salles = $salleRepo->findBy(['proprietaire' => $user]);

        return $this->render('proprietaire/salles.html.twig', [
            'salles' => $salles,
        ]);
    }

    #[Route('/salle/creer', name: 'proprietaire_salle_creer')]
    public function createSalle(SluggerInterface $slugger, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('You must be logged in to access this page.');
        }

        $salle = new SalleDeSport();
        $form = $this->createForm(SalleDeSportType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $salle->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('proprietaire_salle_creer');
                }
            }

            $salle->setProprietaire($user);

            $this->entityManager->persist($salle);
            $this->entityManager->flush();

            $this->addFlash('success', 'Salle created successfully!');
            return $this->redirectToRoute('proprietaire_salles');
        }

        return $this->render('proprietaire/creer_salle.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/salle/{id}/supprimer', name: 'proprietaire_salle_supprimer')]
    public function deleteSalle(SalleDeSport $salle): Response
    {
        $user = $this->getUser();

        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.');
        }

        if ($salle->getProprietaire() !== $user) {
            $this->addFlash('error', 'You do not have permission to delete this salle.');
            return $this->redirectToRoute('proprietaire_salles');
        }

        // Remove the salle entity
        $this->entityManager->remove($salle);
        $this->entityManager->flush();

        $this->addFlash('success', 'Salle deleted successfully.');
        return $this->redirectToRoute('proprietaire_salles');
    }
    #[Route('/salle/{id}/modifier', name: 'proprietaire_salle_modifier')]
    public function editSalle(SluggerInterface $slugger, Request $request, SalleDeSport $salle): Response
    {
        $user = $this->getUser();

        if (!$user instanceof ProprietaireSalle) {
            throw new AccessDeniedException('Seuls les propriétaires peuvent accéder à cette page.');
        }

        if ($salle->getProprietaire() !== $user) {
            $this->addFlash('error', 'Vous n\'avez pas la permission de modifier cette salle.');
            return $this->redirectToRoute('proprietaire_salles');
        }

        $form = $this->createForm(SalleDeSportType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                    $salle->setImage($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('proprietaire_salle_modifier', ['id' => $salle->getId()]);
                }
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Salle mise à jour avec succès !');
            return $this->redirectToRoute('proprietaire_salles');
        }

        return $this->render('proprietaire/modifier_salle.html.twig', [
            'form' => $form->createView(),
            'salle' => $salle,
        ]);
    }
    #[Route('/salles_all', name: 'salles_list')]
    public function listAllSalles(SalleDeSportRepository $salleRepo): Response
    {
        // Fetch all Salles from the database
        $salles = $salleRepo->findAll();

        return $this->render('proprietaire/list_all.html.twig', [
            'salles' => $salles,
        ]);
    }
    #[Route('/proprietaire/salle/{id}/abonnements', name: 'proprietaire_salle_abonnements')]
    public function showAbonnements(SalleDeSport $salle): Response
    {
        $abonnements = $this->entityManager
            ->getRepository(Abonnement::class)
            ->findBy(['salle' => $salle]);

        return $this->render('proprietaire/salle_abonnements.html.twig', [
            'salle' => $salle,
            'abonnements' => $abonnements,
        ]);
    }
}
