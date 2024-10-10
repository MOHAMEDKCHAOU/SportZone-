<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // Constructor to initialize properties if needed
    public function __construct()
    {
        parent::__construct(); // Call to the parent User constructor
    }

    /**
     * Manage subscriptions (GererAbonnement)
     */
    public function GererAbonnement(): void
    {
        // Implementation logic for managing subscriptions
        // This method can be fleshed out to include actual logic
    }

    /**
     * Manage gyms (GererSalleDeSport)
     */
    public function GererSalleDeSport(): void
    {
        // Implementation logic for managing gyms
        // This method can be fleshed out to include actual logic
    }

    // Getters and setters, if needed

    public function getId(): ?int
    {
        return $this->id;
    }
}
