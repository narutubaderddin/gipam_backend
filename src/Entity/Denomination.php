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
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="denominations")
     */
    private $domaine;

    /**
     * @ORM\ManyToMany(targetEntity=MaterialTechnique::class, inversedBy="denominations")
     */
    private $materialsTechniques;

    /**
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="denomination")
     */
    private $furniture;

    public function __construct()
    {
        $this->materialsTechniques = new ArrayCollection();
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDomaine(): ?Field
    {
        return $this->domaine;
    }

    public function setDomaine(?Field $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * @return Collection|MaterialTechnique[]
     */
    public function getMaterialsTechniques(): Collection
    {
        return $this->materialsTechniques;
    }

    public function addMaterialsTechnique(MaterialTechnique $materialsTechnique): self
    {
        if (!$this->materialsTechniques->contains($materialsTechnique)) {
            $this->materialsTechniques[] = $materialsTechnique;
        }

        return $this;
    }

    public function removeMaterialsTechnique(MaterialTechnique $materialsTechnique): self
    {
        $this->materialsTechniques->removeElement($materialsTechnique);

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
