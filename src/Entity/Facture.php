<?php
namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;

#[ORM\Column(type: 'float')]
private ?float $montantHT = null;

#[ORM\Column(type: 'float')]
private ?float $tva = null;

#[ORM\Column(type: 'float')]
private ?float $montantTotal = null;

#[ORM\Column(type: Types::TEXT)]
private ?string $description = null;

#[ORM\OneToOne(targetEntity: Abonnement::class, inversedBy: 'facture')]
#[ORM\JoinColumn(nullable: false)]
private ?Abonnement $abonnement = null;



public function getId(): ?int
{
return $this->id;
}

public function getMontantHT(): ?float
{
return $this->montantHT;
}

public function setMontantHT(float $montantHT): self
{
$this->montantHT = $montantHT;

return $this;
}

public function getTva(): ?float
{
return $this->tva;
}

public function setTva(float $tva): self
{
$this->tva = $tva;

return $this;
}

public function getMontantTotal(): ?float
{
return $this->montantTotal;
}

public function setMontantTotal(float $montantTotal): self
{
$this->montantTotal = $montantTotal;

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

public function getAbonnement(): ?Abonnement
{
return $this->abonnement;
}

public function setAbonnement(?Abonnement $abonnement): self
{
$this->abonnement = $abonnement;

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
}
