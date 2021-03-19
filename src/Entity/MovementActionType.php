<?php

namespace App\Entity;

use App\Repository\MovementActionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovementActionTypeRepository::class)
 */
class MovementActionType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=MovementType::class, inversedBy="movementActionTypes")
     */
    private $movementType;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="movementActionType")
     */
    private $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
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

    public function getMovementType(): ?MovementType
    {
        return $this->movementType;
    }

    public function setMovementType(?MovementType $movementType): self
    {
        $this->movementType = $movementType;

        return $this;
    }

    /**
     * @return Collection|Action[]
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->setMovementActionType($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getMovementActionType() === $this) {
                $action->setMovementActionType(null);
            }
        }

        return $this;
    }
}
