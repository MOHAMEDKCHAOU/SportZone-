<?php

namespace App\Controller\Admin;

use App\Entity\ProprietaireSalle;
use App\Entity\SalleDeSport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SalleDeSportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SalleDeSport::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nomSalle', 'Nom de la Salle'),
            TextField::new('adresse', 'Adresse'),
            TextField::new('numTel', 'Numéro de Téléphone'),
            TimeField::new('heureOuverture', 'Heure d\'Ouverture'),
            TimeField::new('heureFermeture', 'Heure de Fermeture'),
            ImageField::new('image', 'Image')
                ->setBasePath('uploads/salles')
                ->setUploadDir('public/uploads/salles')
                ->setRequired(false)

        ];
    }





    public function createSalleDeSport(string $entityFqcn): Response
    {
        $entity = parent::createEntity($entityFqcn);
        if ($entity instanceof SalleDeSport) {

            if ($entity !== null && !$entity instanceof SalleDeSport) {
                // Debug output
                dump($entity);
            }
        }
        return $entity;
    }
}
