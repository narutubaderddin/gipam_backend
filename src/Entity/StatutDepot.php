<?php

namespace App\Entity;

use App\Repository\StatutDepotRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatutDepotRepository::class)
 */
class StatutDepot extends Statut
{
    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $numeroInventaire;

    /**
     * @ORM\ManyToOne(targetEntity=Deposant::class, inversedBy="statutDepots")
     */
    private $deposant;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDepot;

    public function getNumeroInventaire(): ?string
    {
        return $this->numeroInventaire;
    }

    public function setNumeroInventaire(?string $numeroInventaire): self
    {
        $this->numeroInventaire = $numeroInventaire;

        return $this;
    }

    public function getDeposant(): ?Deposant
    {
        return $this->deposant;
    }

    public function setDeposant(?Deposant $deposant): self
    {
        $this->deposant = $deposant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(?\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }
}
