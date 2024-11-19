<?php
namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Abonnement
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;

#[ORM\Column(length: 255)]
#[Assert\NotBlank(message: 'Le nom ne peut pas être vide.')]
#[Assert\Length(
max: 255,
maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
)]
private ?string $nom = null;

#[ORM\Column(type: 'text')]
private ?string $description = null;

#[ORM\Column(type: 'float')]
#[Assert\NotNull(message: 'Le prix doit être défini.')]
#[Assert\Positive(message: 'Le prix doit être supérieur à zéro.')]
private ?float $prix = null;

#[ORM\Column(type: 'datetime')]
#[Assert\NotNull(message: 'La date de début est requise.')]
private ?\DateTimeInterface $dateDebut = null;

#[ORM\Column(type: 'datetime')]
#[Assert\NotNull(message: 'La date de fin est requise.')]
#[Assert\GreaterThan(
propertyPath: 'dateDebut',
message: 'La date de fin doit être après la date de début.'
)]
private ?\DateTimeInterface $dateFin = null;

#[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'abonnements')]
#[ORM\JoinColumn(nullable: true)]
private ?Client $client = null;

#[ORM\ManyToOne(targetEntity: SalleDeSport::class, inversedBy: 'abonnements')]
#[ORM\JoinColumn(nullable: false)]
private ?SalleDeSport $salle = null;

#[ORM\ManyToOne(targetEntity: Service::class, inversedBy: 'abonnements')]
#[ORM\JoinColumn(nullable: true)]
private ?Service $service = null;

#[ORM\OneToOne(targetEntity: Facture::class, mappedBy: 'abonnement')]
private ?Facture $facture = null;

#[ORM\PrePersist]
public function prePersist(): void
{
if ($this->dateDebut === null) {
$this->dateDebut = new \DateTime();
}
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

public function getSalle(): ?SalleDeSport
{
return $this->salle;
}

public function setSalle(?SalleDeSport $salle): self
{
$this->salle = $salle;
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
