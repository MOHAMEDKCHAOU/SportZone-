<?php
namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;

#[ORM\Column(length: 255)]
private ?string $nom = null;

#[ORM\Column(type: 'text')]
private ?string $description = null;

#[ORM\Column(type: 'float')]
private ?float $prix = null;

#[ORM\Column(type: 'datetime')]
private ?\DateTimeInterface $dateDebut = null;

#[ORM\Column(type: 'datetime')]
private ?\DateTimeInterface $dateFin = null;

#[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'abonnements')]
#[ORM\JoinColumn(nullable: false)]
private ?Client $client = null;

#[ORM\ManyToOne(targetEntity: SalleDeSport::class, inversedBy: 'abonnements')]
private Collection $salles;

#[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'abonnements')]
#[ORM\JoinColumn(nullable: false)]
private ?Service $service = null;

#[ORM\OneToOne(targetEntity: Facture::class, mappedBy: 'abonnement')]
private ?Facture $facture = null; // Change to a single Facture instance

public function __construct()
{
$this->salles = new ArrayCollection();
}

public function getId(): ?int
{
return $this->id;
}

public function getNom(): ?string
{
return $this->nom;
}

public function setNom(string $nom): self
{
$this->nom = $nom;
return $this;
}

public function getDescription(): ?string
{
return $this->description;
}

public function setDescription(string $description): self
{
$this->description = $description;
return $this;
}

public function getPrix(): ?float
{
return $this->prix;
}

public function setPrix(float $prix): self
{
$this->prix = $prix;
return $this;
}

public function getDateDebut(): ?\DateTimeInterface
{
return $this->dateDebut;
}

public function setDateDebut(\DateTimeInterface $dateDebut): self
{
$this->dateDebut = $dateDebut;
return $this;
}

public function getDateFin(): ?\DateTimeInterface
{
return $this->dateFin;
}

public function setDateFin(\DateTimeInterface $dateFin): self
{
$this->dateFin = $dateFin;
return $this;
}

public function getClient(): ?Client
{
return $this->client;
}

public function setClient(?Client $client): self
{
$this->client = $client;
return $this;
}

/**
* @return Collection<int, SalleDeSport>
*/
public function getSalles(): Collection
{
return $this->salles;
}

public function addSalle(SalleDeSport $salle): self
{
if (!$this->salles->contains($salle)) {
$this->salles[] = $salle;
$salle->addAbonnement($this); // Ensures bi-directional relationship
}
return $this;
}

public function removeSalle(SalleDeSport $salle): self
{
if ($this->salles->removeElement($salle)) {
$salle->removeAbonnement($this); // Ensures bi-directional relationship
}
return $this;
}

public function getService(): ?Service
{
return $this->service;
}

public function setService(?Service $service): self
{
$this->service = $service;
return $this;
}

public function getFacture(): ?Facture
{
return $this->facture;
}

public function setFacture(?Facture $facture): self
{
$this->facture = $facture;
return $this;
}
}
