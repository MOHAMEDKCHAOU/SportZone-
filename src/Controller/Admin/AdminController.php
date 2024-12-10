<?php
namespace App\Controller\Admin;

use App\Controller\Admin\ProprietaireSalleCrudController;
use App\Controller\Admin\SalleDeSportCrudController;
use App\Entity\ProprietaireSalle;
use App\Entity\SalleDeSport;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security; // Import the Security service

class AdminController extends AbstractDashboardController
{
private $security;

// Inject the Security service through the constructor
public function __construct(Security $security)
{
$this->security = $security;
}

#[Route('/admin/prop', name: 'adminp')]
public function indexProp(): Response
{
$routeBuilder = $this->container->get(AdminUrlGenerator::class);

return $this->redirect($routeBuilder->setController(ProprietaireSalleCrudController::class)->generateUrl());
}


    #[Route('/admin/salle', name: 'admins')]
    public function indexSalle(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect($routeBuilder->setController(ClientCrudController::class)->generateUrl());

    }

public function configureMenuItems(): iterable
{
return [
// Dashboard Link
yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

// Links to CRUD Controllers
yield MenuItem::linkToCrud('Propriétaires', 'fas fa-user-tie', ProprietaireSalle::class),
yield MenuItem::linkToCrud('Salles de Sport', 'fas fa-dumbbell', SalleDeSport::class),
];
}
}
