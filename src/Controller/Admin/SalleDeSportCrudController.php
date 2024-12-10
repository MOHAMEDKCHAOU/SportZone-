<?php

namespace App\Controller\Admin;

use Symfony\Component\Filesystem\Filesystem;
use App\Entity\ProprietaireSalle;
use App\Entity\SalleDeSport;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\HttpFoundation\RequestStack;

class SalleDeSportCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return SalleDeSport::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $filesystem = new Filesystem();
        $uploadDir = 'public/uploads';

        if (!$filesystem->exists($uploadDir)) {
            $filesystem->mkdir($uploadDir, 0755);
        }

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomSalle', 'Nom de la Salle'),
            TextField::new('adresse', 'Adresse'),
            TextField::new('numTel', 'Numéro de Téléphone'),
            TimeField::new('heureOuverture', 'Heure d\'Ouverture'),
            TimeField::new('heureFermeture', 'Heure de Fermeture'),
            ImageField::new('image', 'Image')
                ->setBasePath('uploads/salles')
                ->setUploadDir($uploadDir)
                ->setRequired(false),
            AssociationField::new('proprietaire', 'Propriétaire')
                ->setCrudController(ProprietaireSalleCrudController::class)
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $entity = parent::createEntity($entityFqcn);

        if ($entity instanceof SalleDeSport) {
            // Retrieve the currently selected ProprietaireSalle ID from the request
            $request = $this->requestStack->getCurrentRequest();
            $proprietaireId = $request?->request->get('SalleDeSport')['proprietaire'] ?? null;

            if ($proprietaireId) {
                $proprietaire = $this->entityManager
                    ->getRepository(ProprietaireSalle::class)
                    ->find($proprietaireId);

                if ($proprietaire) {
                    $entity->setProprietaire($proprietaire);
                } else {
                    // Log or handle the case where the ProprietaireSalle was not found
                    throw new \Exception('ProprietaireSalle not found for ID: ' . $proprietaireId);
                }
            }
        }

        return $entity;
    }
}
