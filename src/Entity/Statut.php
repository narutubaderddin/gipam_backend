<?php

// todo: class could be abstract

namespace App\Entity;

use App\Repository\StatutRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatutRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"statutPropriete"="StatutPropriete", "statutDepot"="StatutDepot"})
 */
abstract class Statut
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateFin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=ObjetMobilier::class, mappedBy="statut")
     */
    protected $objetMobiliers;

    public function __construct()
    {
        $this->objetMobiliers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateDebut(): ?DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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
     * @return Collection|ObjetMobilier[]
     */
    public function getObjetMobiliers(): Collection
    {
        return $this->objetMobiliers;
    }

    public function addObjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if (!$this->objetMobiliers->contains($objetMobilier)) {
            $this->objetMobiliers[] = $objetMobilier;
            $objetMobilier->setStatut($this);
        }

        return $this;
    }

    public function removeObjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if ($this->objetMobiliers->removeElement($objetMobilier)) {
            // set the owning side to null (unless already changed)
            if ($objetMobilier->getStatut() === $this) {
                $objetMobilier->setStatut(null);
            }
        }

        return $this;
    }
}
