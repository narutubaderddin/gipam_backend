<?php

namespace App\Entity;

use App\Repository\StatutProprieteRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatutProprieteRepository::class)
 */
class StatutPropriete extends Statut
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEntree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marquage;

    /**
     * @ORM\ManyToOne(targetEntity=ModeEntree::class, inversedBy="statutProprietes")
     */
    private $modeEntree;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="statutProprietes")
     */
    private $categorie;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $propUnPourCent;

    public function getDateEntree(): ?DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(?DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getMarquage(): ?string
    {
        return $this->marquage;
    }

    public function setMarquage(?string $marquage): self
    {
        $this->marquage = $marquage;

        return $this;
    }

    public function getModeEntree(): ?ModeEntree
    {
        return $this->modeEntree;
    }

    public function setModeEntree(?ModeEntree $modeEntree): self
    {
        $this->modeEntree = $modeEntree;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPropUnPourCent(): ?bool
    {
        return $this->propUnPourCent;
    }

    public function setPropUnPourCent(?bool $propUnPourCent): self
    {
        $this->propUnPourCent = $propUnPourCent;

        return $this;
    }
}
