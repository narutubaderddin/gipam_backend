<?php

namespace App\Entity;

use App\Repository\MaterialTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=MaterialTechniqueRepository::class)
 * @ORM\Table(name="matiere_technique")
 */
class MaterialTechnique
{
    /**
     * @JMS\Groups("id", "material_technique")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("material_technique")
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Groups("material_technique")
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @JMS\Groups("material_technique", "id")
     *
     * @ORM\ManyToMany(targetEntity=Denomination::class, mappedBy="materialsTechniques")
     */
    private $denominations;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="materialTechnique")
     */
    private $furniture;

    /**
     * @JMS\Groups("material_technique")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->denominations = new ArrayCollection();
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
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
            $denomination->addMaterialsTechnique($this);
        }

        return $this;
    }

    public function removeDenomination(Denomination $denomination): self
    {
        if ($this->denominations->contains($denomination)) {
            $this->denominations->removeElement($denomination);
            $denomination->removeMaterialsTechnique($this);
        }

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
            $furniture->setMaterialTechnique($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getMaterialTechnique() === $this) {
                $furniture->setMaterialTechnique(null);
            }
        }

        return $this;
    }
}
