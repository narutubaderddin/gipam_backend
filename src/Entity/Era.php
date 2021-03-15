<?php

namespace App\Entity;

use App\Repository\EraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EraRepository::class)
 */
class Era
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
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="era")
     */
    private $furnitures;

    public function __construct()
    {
        $this->furnitures = new ArrayCollection();
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
     * @return Collection|Furniture[]
     */
    public function getFurnitures(): Collection
    {
        return $this->furnitures;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furnitures->contains($furniture)) {
            $this->furnitures[] = $furniture;
            $furniture->setEra($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furnitures->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getEra() === $this) {
                $furniture->setEra(null);
            }
        }

        return $this;
    }
}
