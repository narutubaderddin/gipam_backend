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
     * @ORM\Column(name="commentaire" ,type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(name="date_debut" ,type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(name="date_fin" ,type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(name="delai" ,type="integer")
     */
    private $period;

    /**
     * @ORM\Column(name="nature_action" ,type="string", length=255, nullable=true)
     */
    private $actionNature;

    /**
     * @ORM\ManyToOne(targetEntity=ActionReportType::class, inversedBy="actions")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Movement::class, inversedBy="actions")
     * @ORM\JoinColumn(name="mouvement_id", referencedColumnName="id")
     */
    private $movement;

    /**
     * @ORM\ManyToOne(targetEntity=MovementActionType::class, inversedBy="actions")
     * @ORM\JoinColumn(name="type_mouvement_action_id", referencedColumnName="id")
     */
    private $movementActionType;

    /**
     * @ORM\ManyToOne(targetEntity=Report::class, inversedBy="actions")
     * @ORM\JoinColumn(name="constat_id", referencedColumnName="id")
     */
    private $report;

    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="action")
     */
    private $alerts;

    public function __construct()
    {
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

    public function getType(): ?ActionReportType
    {
        return $this->type;
    }

    public function setType(?ActionReportType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Movement|null
     */
    public function getMovement(): ?Movement
    {
        return $this->movement;
    }

    public function setMovement(?Movement $movement): self
    {
        $this->movement = $movement;

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
