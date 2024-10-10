<?php

namespace App\Entity;

use App\Repository\ProprietaireSalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietaireSalleRepository::class)]
class ProprietaireSalle extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'proprietaire', targetEntity: SalleDeSport::class)]
    private Collection $salles;

    public function __construct()
    {
        parent::__construct(); // Call to the parent User constructor
        $this->salles = new ArrayCollection();
    }

    /**
     * Manage subscriptions
     */
    public function gererAbonnement(): void
    {
        // Implementation logic for managing subscriptions
    }

    /**
     * Manage gyms
     */
    public function gererSalleDeSport(): void
    {
        // Implementation logic for managing gyms
    }

    /**
     * Get the ID of the ProprietaireSalle
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the collection of gyms (salles)
     *
     * @return Collection<int, SalleDeSport>
     */
    public function getSalles(): Collection
    {
        return $this->salles;
    }

    /**
     * Add a new gym (SalleDeSport) to the owner
     */
    public function addSalle(SalleDeSport $salle): self
    {
        if (!$this->salles->contains($salle)) {
            $this->salles[] = $salle;
            $salle->setProprietaire($this);
        }

        return $this;
    }

    /**
     * Remove a gym (SalleDeSport) from the owner
     */
    public function removeSalle(SalleDeSport $salle): self
    {
        if ($this->salles->removeElement($salle)) {
            // Set the owning side to null (unless already changed)
            if ($salle->getProprietaire() === $this) {
                $salle->setProprietaire(null);
            }
        }

        return $this;
    }
}
