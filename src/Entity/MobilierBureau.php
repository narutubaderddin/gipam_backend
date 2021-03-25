<?php

namespace App\Entity;

use App\Repository\MobilierBureauRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MobilierBureauRepository::class)
 */
class MobilierBureau extends ObjetMobilier
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fournisseur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixAchat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $volumeUnitaire;

    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?string $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?float $prixAchat): self
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getVolumeUnitaire(): ?float
    {
        return $this->volumeUnitaire;
    }

    public function setVolumeUnitaire(?float $volumeUnitaire): self
    {
        $this->volumeUnitaire = $volumeUnitaire;

        return $this;
    }
}
