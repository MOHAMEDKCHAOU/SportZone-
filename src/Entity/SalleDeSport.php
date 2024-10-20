<?php
namespace App\Entity;

use App\Repository\SalleDeSportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleDeSportRepository::class)]
class SalleDeSport
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;

#[ORM\Column(length: 150)]
private ?string $nomSalle = null;

#[ORM\Column(length: 255)]
private ?string $adresse = null;

#[ORM\Column(length: 15)]
private ?string $numTel = null;

#[ORM\Column(type: 'time')]
private ?\DateTimeInterface $heureOuverture = null;

#[ORM\Column(type: 'time')]
private ?\DateTimeInterface $heureFermeture = null;

#[ORM\ManyToOne(targetEntity: ProprietaireSalle::class, inversedBy: 'salles')]
#[ORM\JoinColumn(nullable: false)]
private ?ProprietaireSalle $proprietaire = null;

#[ORM\ManyToMany(targetEntity: Abonnement::class, inversedBy: 'salles')]
private Collection $abonnements;

public function __construct()
{
$this->abonnements = new ArrayCollection();
}

public function getId(): ?int
{
return $this->id;
}

public function getNomSalle(): ?string
{
return $this->nomSalle;
}

public function setNomSalle(string $nomSalle): self
{
$this->nomSalle = $nomSalle;
return $this;
}

public function getAdresse(): ?string
{
return $this->adresse;
}

public function setAdresse(string $adresse): self
{
$this->adresse = $adresse;
return $this;
}

public function getNumTel(): ?string
{
return $this->numTel;
}

public function setNumTel(string $numTel): self
{
$this->numTel = $numTel;
return $this;
}

public function getHeureOuverture(): ?\DateTimeInterface
{
return $this->heureOuverture;
}

public function setHeureOuverture(\DateTimeInterface $heureOuverture): self
{
$this->heureOuverture = $heureOuverture;
return $this;
}

public function getHeureFermeture(): ?\DateTimeInterface
{
return $this->heureFermeture;
}

public function setHeureFermeture(\DateTimeInterface $heureFermeture): self
{
$this->heureFermeture = $heureFermeture;
return $this;
}

public function getProprietaire(): ?ProprietaireSalle
{
return $this->proprietaire;
}

public function setProprietaire(?ProprietaireSalle $proprietaire): self
{
$this->proprietaire = $proprietaire;
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
$abonnement->addSalle($this); // Ensures bi-directional relationship
}
return $this;
}

public function removeAbonnement(Abonnement $abonnement): self
{
if ($this->abonnements->removeElement($abonnement)) {
$abonnement->removeSalle($this); // Ensures bi-directional relationship
}
return $this;
}
}
