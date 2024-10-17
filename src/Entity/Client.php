<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{
    #[ORM\Column(length: 15)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Abonnement::class)]
    private Collection $abonnements;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Facture::class)]
    private Collection $factures;

    public function __construct()
    {
        parent::__construct(); // Call parent constructor if needed
        $this->abonnements = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    // Remove the getId method as it's inherited from User
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return Collection<int, Abonnement>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setClient($this);
        }
        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // Set the owning side to null (unless already changed)
            if ($abonnement->getClient() === $this) {
                $abonnement->setClient(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setClient($this);
        }
        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // Set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }
        return $this;
    }
}
