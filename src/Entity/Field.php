<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=FieldRepository::class)
 * @ORM\Table(name="domaine")
 */
class Field
{
    /**
     * @JMS\Groups("id", "field")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("field")
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Groups("field")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Denomination::class, mappedBy="field")
     */
    private $denominations;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="field")
     */
    private $furniture;

    public function __construct()
    {
        $this->denominations = new ArrayCollection();
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
            $denomination->setField($this);
        }

        return $this;
    }

    public function removeDenomination(Denomination $denomination): self
    {
        if ($this->denominations->removeElement($denomination)) {
            // set the owning side to null (unless already changed)
            if ($denomination->getField() === $this) {
                $denomination->setField(null);
            }
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
            $furniture->setField($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getField() === $this) {
                $furniture->setField(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
