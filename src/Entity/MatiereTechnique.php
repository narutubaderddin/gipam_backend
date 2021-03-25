<?php

namespace App\Entity;

use App\Repository\MatiereTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MatiereTechniqueRepository::class)
 */
class MatiereTechnique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Denomination::class, mappedBy="materialsTechniques")
     */
    private $denominations;

    /**
     * @ORM\OneToMany(targetEntity=ObjetMobilier::class, mappedBy="materialTechnique")
     */
    private $objetMobiliers;

    public function __construct()
    {
        $this->denominations = new ArrayCollection();
        $this->objetMobiliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Denomination[]
     */
    public function getDenominations(): Collection
    {
        return $this->denominations;
    }

    public function addDenomination(Denomination $denomination): self
    {
        if (!$this->denominations->contains($denomination)) {
            $this->denominations[] = $denomination;
            $denomination->addMatiereTechnique($this);
        }

        return $this;
    }

    public function removeDenomination(Denomination $denomination): self
    {
        if ($this->denominations->removeElement($denomination)) {
            $denomination->removeMatiereTechnique($this);
        }

        return $this;
    }

    /**
     * @return Collection|ObjetMobilier[]
     */
    public function getObjetMobiliers(): Collection
    {
        return $this->objetMobiliers;
    }

    public function addObjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if (!$this->objetMobiliers->contains($objetMobilier)) {
            $this->objetMobiliers[] = $objetMobilier;
            $objetMobilier->setMatiereTechnique($this);
        }

        return $this;
    }

    public function removeObjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if ($this->objetMobiliers->removeElement($objetMobilier)) {
            // set the owning side to null (unless already changed)
            if ($objetMobilier->getMatiereTechnique() === $this) {
                $objetMobilier->setMatiereTechnique(null);
            }
        }

        return $this;
    }
}
