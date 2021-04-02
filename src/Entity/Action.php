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
    private $commentaire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="integer")
     */
    private $delai;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $natureAction;

    /**
     * @ORM\ManyToOne(targetEntity=TypeAction::class, inversedBy="actions")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=TypeMouvementAction::class, inversedBy="actions")
     */
    private $typeMouvementAction;

    /**
     * @ORM\ManyToOne(targetEntity=Constat::class, inversedBy="actions")
     */
    private $constat;

    /**
     * @ORM\OneToMany(targetEntity=Alerte::class, mappedBy="action")
     */
    private $alertes;

    /**
     * @ORM\ManyToOne(targetEntity=Mouvement::class, inversedBy="actions")
     */
    private $mouvement;

    public function __construct()
    {
        $this->alertes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDebut(): ?DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDelai(): ?int
    {
        return $this->delai;
    }

    public function setDelai(int $delai): self
    {
        $this->delai = $delai;

        return $this;
    }

    public function getNatureAction(): ?string
    {
        return $this->natureAction;
    }

    public function setNatureAction(?string $natureAction): self
    {
        $this->natureAction = $natureAction;

        return $this;
    }

    public function getType(): ?TypeAction
    {
        return $this->type;
    }

    public function setType(?TypeAction $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeMouvementAction(): ?TypeMouvementAction
    {
        return $this->typeMouvementAction;
    }

    public function setTypeMouvementAction(?TypeMouvementAction $typeMouvementAction): self
    {
        $this->typeMouvementAction = $typeMouvementAction;

        return $this;
    }

    public function getConstat(): ?Constat
    {
        return $this->constat;
    }

    public function setConstat(?Constat $constat): self
    {
        $this->constat = $constat;

        return $this;
    }

    /**
     * @return Collection|Alerte[]
     */
    public function getAlertes(): Collection
    {
        return $this->alertes;
    }

    public function addAlerte(Alerte $alerte): self
    {
        if (!$this->alertes->contains($alerte)) {
            $this->alertes[] = $alerte;
            $alerte->setAction($this);
        }

        return $this;
    }

    public function removeAlerte(Alerte $alerte): self
    {
        if ($this->alertes->removeElement($alerte)) {
            // set the owning side to null (unless already changed)
            if ($alerte->getAction() === $this) {
                $alerte->setAction(null);
            }
        }

        return $this;
    }

    public function getMouvement(): ?Mouvement
    {
        return $this->mouvement;
    }

    public function setMouvement(?Mouvement $mouvement): self
    {
        $this->mouvement = $mouvement;

        return $this;
    }
}
