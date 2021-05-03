<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\PropertyStatusCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=PropertyStatusCategoryRepository::class)
 * @ORM\Table(name="categorie")
 */
class PropertyStatusCategory
{
    use TimestampableEntity;

    public const LABEL = [
        'bienRemarquable' => 'Bien Patrimonial remarquable',
        'bienStandard' => 'Bien Patrimonial standard',
        'bienUsuel' => 'Bien usuel',
    ];
    /**
     * @JMS\Groups("id", "category", "artwork")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("category")
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=PropertyStatus::class, mappedBy="category")
     */
    private $propertyStatuses;

    /**
     * @JMS\Groups("category")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->propertyStatuses = new ArrayCollection();
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
