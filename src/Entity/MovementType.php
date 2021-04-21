<?php

namespace App\Entity;

use App\Repository\MovementTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MovementTypeRepository::class)
 * @ORM\Table(name="type_mouvement")
 */
class MovementType
{
    public const LIBELLE = [
        'installation' => 'Installation',
        'reserve' => 'Mise en réserve',
        'temporaire' => 'Sortie temporaire',
        'definitive' => 'Sortie définitive',
    ];
    /**
     * @JMS\Groups("id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("movement_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="libelle", type="string", length=150, nullable=true)
     */
    private $label;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Movement::class, mappedBy="type")
     */
    private $movements;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=MovementActionType::class, mappedBy="movementType")
     */
    private $movementActionTypes;

    /**
     * @JMS\Groups("movement_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
        $this->movementActionTypes = new ArrayCollection();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Movement[]
     */
    public function getMovements(): Collection
    {
        return $this->movements;
    }

    public function addMovement(Movement $movement): self
    {
        if (!$this->movements->contains($movement)) {
            $this->movements[] = $movement;
            $movement->setType($this);
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        if ($this->movements->removeElement($movement)) {
            // set the owning side to null (unless already changed)
            if ($movement->getType() === $this) {
                $movement->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MovementActionType[]
     */
    public function getMovementActionTypes(): Collection
    {
        return $this->movementActionTypes;
    }

    public function addMovementActionType(MovementActionType $movementActionType): self
    {
        if (!$this->movementActionTypes->contains($movementActionType)) {
            $this->movementActionTypes[] = $movementActionType;
            $movementActionType->setMovementType($this);
        }

        return $this;
    }

    public function removeMovementActionType(MovementActionType $movementActionType): self
    {
        if ($this->movementActionTypes->removeElement($movementActionType)) {
            // set the owning side to null (unless already changed)
            if ($movementActionType->getMovementType() === $this) {
                $movementActionType->setMovementType(null);
            }
        }

        return $this;
    }
}
