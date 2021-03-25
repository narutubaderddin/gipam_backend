<?php

namespace App\Entity;

use App\Repository\SousDirectionRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SousDirectionRepository::class)
 */
class SousDirection
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
    private $dateFin;

    /**
     * @ORM\OneToMany(targetEntity=Localisation::class, mappedBy="sousDirection")
     */
    private $localisations;

    /**
     * @ORM\OneToMany(targetEntity=Correspondant::class, mappedBy="sousDirection")
     */
    private $correspondants;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="sousDirection")
     */
    private $services;

    /**
     * @ORM\ManyToOne(targetEntity=Etablissement::class, inversedBy="$sousDirection")
     */
    private $etablissement;

    public function __construct()
    {
        $this->localisations = new ArrayCollection();
        $this->correspondants = new ArrayCollection();
        $this->services = new ArrayCollection();
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

    public function getDateFin(): ?DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Localisation[]
     */
    public function getLocalisations(): Collection
    {
        return $this->localisations;
    }

    public function addLocalisation(Localisation $localisation): self
    {
        if (!$this->localisations->contains($localisation)) {
            $this->localisations[] = $localisation;
            $localisation->setSousDirection($this);
        }

        return $this;
    }

    public function removeLocalisation(Localisation $localisation): self
    {
        if ($this->localisations->removeElement($localisation)) {
            // set the owning side to null (unless already changed)
            if ($localisation->getSousDirection() === $this) {
                $localisation->setSousDirection(null);
            }
        }

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
            $correspondant->setSousDirection($this);
        }

        return $this;
    }

    public function removeCorrespondant(Correspondant $correspondant): self
    {
        if ($this->correspondants->removeElement($correspondant)) {
            // set the owning side to null (unless already changed)
            if ($correspondant->getSousDirection() === $this) {
                $correspondant->setSousDirection(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setSousDirection($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getSousDirection() === $this) {
                $service->setSousDirection(null);
            }
        }

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }
}
