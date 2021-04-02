<?php

namespace App\Entity;

use App\Repository\LocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocalisationRepository::class)
 */
class Localisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Etablissement::class, inversedBy="localisations")
     */
    private $etablissement;

    /**
     * @ORM\ManyToOne(targetEntity=SousDirection::class, inversedBy="localisations")
     */
    private $sousDirection;

    /**
     * @ORM\OneToMany(targetEntity=Mouvement::class, mappedBy="localisation")
     */
    private $mouvements;

    /**
     * @ORM\ManyToOne(targetEntity=TypeLocalisation::class, inversedBy="localisations")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Piece::class, inversedBy="localisations")
     */
    private $piece;

    public function __construct()
    {
        $this->mouvements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    public function getSousDirection(): ?SousDirection
    {
        return $this->sousDirection;
    }

    public function setSousDirection(?SousDirection $sousDirection): self
    {
        $this->sousDirection = $sousDirection;

        return $this;
    }

    /**
     * @return Collection|Mouvement[]
     */
    public function getMouvements(): Collection
    {
        return $this->mouvements;
    }

    public function addMouvement(Mouvement $mouvement): self
    {
        if (!$this->mouvements->contains($mouvement)) {
            $this->mouvements[] = $mouvement;
            $mouvement->setLocalisation($this);
        }

        return $this;
    }

    public function removeMouvement(Mouvement $mouvement): self
    {
        if ($this->mouvements->removeElement($mouvement)) {
            // set the owning side to null (unless already changed)
            if ($mouvement->getLocalisation() === $this) {
                $mouvement->setLocalisation(null);
            }
        }

        return $this;
    }

    public function getType(): ?TypeLocalisation
    {
        return $this->type;
    }

    public function setType(?TypeLocalisation $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPiece(): ?Piece
    {
        return $this->piece;
    }

    public function setPiece(?Piece $piece): self
    {
        $this->piece = $piece;

        return $this;
    }
}
