<?php

namespace App\Entity;

use App\Repository\MovementTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovementTypeRepository::class)
 */
class MovementType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Movement::class, mappedBy="type")
     */
    private $movements;

    /**
     * @ORM\OneToMany(targetEntity=MovementActionType::class, mappedBy="movementType")
     */
    private $movementActionTypes;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
        $this->movementActionTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?\DateTimeInterface
    {
        return $this->label;
    }

    public function setLabel(?\DateTimeInterface $label): self
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
