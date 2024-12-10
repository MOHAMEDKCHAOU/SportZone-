<?php

namespace App\Controller\Admin;

use App\Entity\ProprietaireSalle;
use App\Entity\SalleDeSport;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProprietaireSalleCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return ProprietaireSalle::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom'),
            TextField::new('prenom', 'Prénom'),
            TextField::new('email', 'Email'),
            TextField::new('adresse', 'Adresse'),
            TextField::new('password', 'Mot de passe')->hideOnIndex()->setFormTypeOption('mapped', true),
            // AssociationField for managing the relationship with SalleDeSport
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $entity = parent::createEntity($entityFqcn);
        if ($entity instanceof ProprietaireSalle) {
            $entity->setPassword(
                $this->passwordHasher->hashPassword(
                    $entity,
                    $entity->getPassword() // Retrieve the plain password from the entity
                )
            );
        }

        return $entity;
    }

    public function updateEntity($entityInstance): void
    {
        if ($entityInstance instanceof ProprietaireSalle) {
            $entityInstance->setPassword(
                $this->passwordHasher->hashPassword(
                    $entityInstance,
                    $entityInstance->getPassword() // Retrieve the plain password from the entity
                )
            );
        }

        parent::updateEntity($entityInstance);
    }
}
