<?php

namespace App\Entity;

use App\Repository\OfficeFurnitureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OfficeFurnitureRepository::class)
 * @ORM\Table(name="mobilier_bureau")
 */
class OfficeFurniture extends Furniture
{
    /**
     * @ORM\Column(name="fournisseur", type="string", length=255, nullable=true)
     */
    private $supplier;

    /**
     * @ORM\Column(name="prix_achat", type="float", nullable=true)
     */
    private $buyingPrice;

    /**
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(name="volume_unitaire", type="float", nullable=true)
     */
    private $unitVolume;

    public function getSupplier(): ?string
    {
        return $this->supplier;
    }

    public function setSupplier(?string $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getBuyingPrice(): ?float
    {
        return $this->buyingPrice;
    }

    public function setBuyingPrice(?float $buyingPrice): self
    {
        $this->buyingPrice = $buyingPrice;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUnitVolume(): ?float
    {
        return $this->unitVolume;
    }

    public function setUnitVolume(?float $unitVolume): self
    {
        $this->unitVolume = $unitVolume;

        return $this;
    }
}
