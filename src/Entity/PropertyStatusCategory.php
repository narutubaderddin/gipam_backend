<?php

namespace App\Entity;

use App\Repository\PropertyStatusCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyStatusCategoryRepository::class)
 */
class PropertyStatusCategory
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
     * @ORM\OneToMany(targetEntity=PropertyStatus::class, mappedBy="category")
     */
    private $propertyStatuses;

    public function __construct()
    {
        $this->propertyStatuses = new ArrayCollection();
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
     * @return Collection|PropertyStatus[]
     */
    public function getPropertyStatuses(): Collection
    {
        return $this->propertyStatuses;
    }

    public function addPropertyStatus(PropertyStatus $propertyStatus): self
    {
        if (!$this->propertyStatuses->contains($propertyStatus)) {
            $this->propertyStatuses[] = $propertyStatus;
            $propertyStatus->setCategory($this);
        }

        return $this;
    }

    public function removePropertyStatus(PropertyStatus $propertyStatus): self
    {
        if ($this->propertyStatuses->removeElement($propertyStatus)) {
            // set the owning side to null (unless already changed)
            if ($propertyStatus->getCategory() === $this) {
                $propertyStatus->setCategory(null);
            }
        }

        return $this;
    }
}
