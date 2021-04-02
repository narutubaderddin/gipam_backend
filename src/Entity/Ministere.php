<?php

namespace App\Entity;

use App\Repository\MinistereRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MinistereRepository::class)
 */
class Ministere
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
    private $nom;

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
     * @ORM\OneToMany(targetEntity=Etablissement::class, mappedBy="ministere")
     */
    private $etablissements;


    public function __construct()
    {
        $this->etablissements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

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
     * @return Collection|Etablissement[]
     */
    public function getEtablissements(): Collection
    {
        return $this->etablissements;
    }

    public function addEtablissement(Etablissement $etablissement): self
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements[] = $etablissement;
            $etablissement->setMinistere($this);
        }

        return $this;
    }

    public function removeEtablissement(Etablissement $etablissement): self
    {
        if ($this->etablissements->removeElement($etablissement)) {
            // set the owning side to null (unless already changed)
            if ($etablissement->getMinistere() === $this) {
                $etablissement->setMinistere(null);
            }
        }

        return $this;
    }
}
