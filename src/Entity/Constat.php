<?php

namespace App\Entity;

use App\Repository\ConstatRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConstatRepository::class)
 */
class Constat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    // todo: meaning and type for titre_percep in UML Class. In SFD "Titre de perception"
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $titrePerception;

    /**
     * @ORM\ManyToOne(targetEntity=SousTypeConstat::class, inversedBy="constats")
     */
    private $sousTypeConstat;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="constat")
     */
    private $actions;

    /**
     * @ORM\ManyToOne(targetEntity=ObjetMobilier::class, inversedBy="reports")
     */
    private $objetMobilier;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getTitrePerception(): ?bool
    {
        return $this->titrePerception;
    }

    public function setTitrePerception(?bool $titrePerception): self
    {
        $this->titrePerception = $titrePerception;

        return $this;
    }

    public function getSousTypeConstat(): ?SousTypeConstat
    {
        return $this->sousTypeConstat;
    }

    public function setSousTypeConstat(?SousTypeConstat $sousTypeConstat): self
    {
        $this->sousTypeConstat = $sousTypeConstat;

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
            $action->setConstat($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getConstat() === $this) {
                $action->setConstat(null);
            }
        }

        return $this;
    }

    public function getObjetMobilier(): ?ObjetMobilier
    {
        return $this->objetMobilier;
    }

    public function setObjetMobilier(?ObjetMobilier $objetMobilier): self
    {
        $this->objetMobilier = $objetMobilier;

        return $this;
    }
}
