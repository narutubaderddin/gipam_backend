<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtablissementRepository::class)
 */
class Etablissement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sigle;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDisparition;

    /**
     * @ORM\OneToMany(targetEntity=Localisation::class, mappedBy="etablissement")
     */
    private $localisations;

    /**
     * @ORM\ManyToOne(targetEntity=Ministere::class, inversedBy="establishments")
     */
    private $ministere;

    /**
     * @ORM\OneToMany(targetEntity=Correspondant::class, mappedBy="etablissement")
     */
    private $correspondants;

    /**
     * @ORM\OneToMany(targetEntity=SousDirection::class, mappedBy="etablissement")
     */
    private $sousDirections;

    /**
     * @ORM\ManyToOne(targetEntity=TypeEtablissement::class, inversedBy="etablissements")
     */
    private $type;

    public function __construct()
    {
        $this->localisations = new ArrayCollection();
        $this->correspondants = new ArrayCollection();
        $this->sousDirections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): self
    {
        $this->sigle = $sigle;

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

    public function getDateDisparition(): ?DateTimeInterface
    {
        return $this->dateDisparition;
    }

    public function setDateDisparition(?DateTimeInterface $dateDisparition): self
    {
        $this->dateDisparition = $dateDisparition;

        return $this;
    }

    /**
     * @return Collection|Localisation[]
     */
    public function getLocalisations(): Collection
    {
        return $this->localisations;
    }

    public function addLocalisations(Localisation $localisation): self
    {
        if (!$this->localisations->contains($localisation)) {
            $this->localisations[] = $localisation;
            $localisation->setEtablissement($this);
        }

        return $this;
    }

    public function removeLocalisation(Localisation $localisation): self
    {
        if ($this->localisations->removeElement($localisation)) {
            // set the owning side to null (unless already changed)
            if ($localisation->getEtablissement() === $this) {
                $localisation->setEtablissement(null);
            }
        }

        return $this;
    }

    public function getMinistere(): ?Ministere
    {
        return $this->ministere;
    }

    public function setMinistere(?Ministere $ministere): self
    {
        $this->ministere = $ministere;

        return $this;
    }

    /**
     * @return Collection|Correspondant[]
     */
    public function getCorrespondants(): Collection
    {
        return $this->correspondants;
    }

    public function addCorrespondant(Correspondant $correspondant): self
    {
        if (!$this->correspondants->contains($correspondant)) {
            $this->correspondants[] = $correspondant;
            $correspondant->setEtablissement($this);
        }

        return $this;
    }

    public function removeCorrespondant(Correspondant $correspondant): self
    {
        if ($this->correspondants->removeElement($correspondant)) {
            // set the owning side to null (unless already changed)
            if ($correspondant->getEtablissement() === $this) {
                $correspondant->setEtablissement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SousDirection[]
     */
    public function getSousDirections(): Collection
    {
        return $this->sousDirections;
    }

    public function addSousDirection(SousDirection $sousDirection): self
    {
        if (!$this->sousDirections->contains($sousDirection)) {
            $this->sousDirections[] = $sousDirection;
            $sousDirection->setEtablissement($this);
        }

        return $this;
    }

    public function removeSubDivision(SousDirection $sousDirection): self
    {
        if ($this->sousDirections->removeElement($sousDirection)) {
            // set the owning side to null (unless already changed)
            if ($sousDirection->getEtablissement() === $this) {
                $sousDirection->setEtablissement(null);
            }
        }

        return $this;
    }

    public function getType(): ?TypeEtablissement
    {
        return $this->type;
    }

    public function setType(?TypeEtablissement $type): self
    {
        $this->type = $type;

        return $this;
    }
}
