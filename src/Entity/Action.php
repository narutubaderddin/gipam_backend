<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionRepository::class)
 */
class Action
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $period;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $actionNature;

    /**
     * @ORM\ManyToOne(targetEntity=ActionType::class, inversedBy="actions")
     */
    private $type;

    /**
     * @ORM\ManyToMany(targetEntity=Movement::class, inversedBy="actions")
     */
    private $movements;

    /**
     * @ORM\ManyToOne(targetEntity=MovementActionType::class, inversedBy="actions")
     */
    private $movementActionType;

    /**
     * @ORM\ManyToOne(targetEntity=Report::class, inversedBy="actions")
     */
    private $report;

    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="action")
     */
    private $alerts;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getActionNature(): ?string
    {
        return $this->actionNature;
    }

    public function setActionNature(?string $actionNature): self
    {
        $this->actionNature = $actionNature;

        return $this;
    }

    public function getType(): ?ActionType
    {
        return $this->type;
    }

    public function setType(?ActionType $type): self
    {
        $this->type = $type;

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
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        $this->movements->removeElement($movement);

        return $this;
    }

    public function getMovementActionType(): ?MovementActionType
    {
        return $this->movementActionType;
    }

    public function setMovementActionType(?MovementActionType $movementActionType): self
    {
        $this->movementActionType = $movementActionType;

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): self
    {
        $this->report = $report;

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setAction($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getAction() === $this) {
                $alert->setAction(null);
            }
        }

        return $this;
    }
}
