<?php

namespace App\Entity;

use App\Repository\DenominationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DenominationRepository::class)
 */
class Denomination
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
     * @ORM\ManyToOne(targetEntity=domaine::class, inversedBy="denominations")
     */
    private $domaine;

    /**
     * @ORM\ManyToMany(targetEntity=MatiereTechnique::class, inversedBy="denominations")
     */
    private $matiereTechniques;

    /**
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="denomination")
     */
    private $furniture;

    public function __construct()
    {
        $this->matiereTechniques = new ArrayCollection();
        $this->furniture = new ArrayCollection();
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

    public function getdomaine(): ?domaine
    {
        return $this->domaine;
    }

    public function setdomaine(?domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * @return Collection|MatiereTechnique[]
     */
    public function getMatiereTechniques(): Collection
    {
        return $this->matiereTechniques;
    }

    public function addMatiereTechnique(MatiereTechnique $matiereTechnique): self
    {
        if (!$this->matiereTechniques->contains($matiereTechnique)) {
            $this->matiereTechniques[] = $matiereTechnique;
        }

        return $this;
    }

    public function removeMatiereTechnique(MatiereTechnique $matiereTechnique): self
    {
        $this->matiereTechniques->removeElement($matiereTechnique);

        return $this;
    }

    /**
     * @return Collection|Furniture[]
     */
    public function getFurniture(): Collection
    {
        return $this->furniture;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furniture->contains($furniture)) {
            $this->furniture[] = $furniture;
            $furniture->setDenomination($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getDenomination() === $this) {
                $furniture->setDenomination(null);
            }
        }

        return $this;
    }
}
