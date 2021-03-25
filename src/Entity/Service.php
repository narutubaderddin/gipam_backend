<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 */
class Service
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
     * @ORM\OneToMany(targetEntity=Correspondant::class, mappedBy="service")
     */
    private $correspondants;

    /**
     * @ORM\ManyToOne(targetEntity=SousDirection::class, inversedBy="services")
     */
    private $sousDirection;

    public function __construct()
    {
        $this->correspondants = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * @param mixed $sigle
     */
    public function setSigle($sigle): void
    {
        $this->sigle = $sigle;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param mixed $dateDebut
     */
    public function setDateDebut($dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return mixed
     */
    public function getDateDisparition()
    {
        return $this->dateDisparition;
    }

    /**
     * @param mixed $dateDisparition
     */
    public function setDateDisparition($dateDisparition): void
    {
        $this->dateDisparition = $dateDisparition;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Correspondant[]
     */
    public function getCorrespondants(): Collection
    {
        return $this->correspondants;
    }

    public function addCorrespondent(Correspondant $correspondant): self
    {
        if (!$this->correspondants->contains($correspondant)) {
            $this->correspondants[] = $correspondant;
            $correspondant->setService($this);
        }

        return $this;
    }

    public function removeCorrespondent(Correspondant $correspondant): self
    {
        if ($this->correspondants->removeElement($correspondant)) {
            // set the owning side to null (unless already changed)
            if ($correspondant->getService() === $this) {
                $correspondant->setService(null);
            }
        }

        return $this;
    }

    public function getSousDirection(): ?SousDirection
    {
        return $this->sousDirection;
    }

    public function setSousDirection(?SousDirection $sousDirection): self
    {
        $this->sousDirection = $sousDirection;

        return $this;
    }
}
