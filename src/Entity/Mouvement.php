<?php

namespace App\Entity;

use App\Repository\MouvementRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MouvementRepository::class)
 */
class Mouvement
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

    /**
     * @ORM\ManyToMany(targetEntity=Correspondant::class, inversedBy="movements")
     */
    private $correspondants;

    /**
     * @ORM\ManyToOne(targetEntity=Localisation::class, inversedBy="mouvements")
     */
    private $localisation;

    /**
     * @ORM\ManyToOne(targetEntity=TypeMouvement::class, inversedBy="movements")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=ObjetMobilier::class, inversedBy="mouvements")
     */
    private $objetMobilier;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="mouvement")
     */
    private $actions;

    public function __construct()
    {
        $this->correspondants = new ArrayCollection();
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

    /**
     * @return Collection|Correspondant[]
     */
    public function getCorrespondants(): Collection
    {
        return $this->correspondants;
    }

    public function addCorrespondant(Correspondant $correspondent): self
    {
        if (!$this->correspondants->contains($correspondent)) {
            $this->correspondants[] = $correspondent;
        }

        return $this;
    }

    public function removeCorrespondant(Correspondant $correspondent): self
    {
        $this->correspondants->removeElement($correspondent);

        return $this;
    }

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getType(): ?TypeMouvement
    {
        return $this->type;
    }

    public function setType(?TypeMouvement $type): self
    {
        $this->type = $type;

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
            $action->setMouvement($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getMouvement() === $this) {
                $action->setMouvement(null);
            }
        }

        return $this;
    }
}
