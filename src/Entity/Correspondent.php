<?php

namespace App\Entity;

use App\Repository\CorrespondentRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CorrespondentRepository::class)
 * @ORM\Table(name="correspondant")
 */
class Correspondent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="correspondents")
     * @ORM\JoinColumn(name="etablissement_id", referencedColumnName="id")
     */
    private $establishment;

    /**
     * @ORM\ManyToOne(targetEntity=SubDivision::class, inversedBy="correspondents")
     * @ORM\JoinColumn(name="sous_direction", referencedColumnName="id")
     */
    private $subDivision;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="correspondents")
     * @ORM\JoinColumn(name="service", referencedColumnName="id")
     */
    private $service;

    /**
     * @ORM\ManyToMany(targetEntity=Movement::class, mappedBy="correspondents")
     */
    private $movements;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEstablishment(): ?Establishment
    {
        return $this->establishment;
    }

    public function setEstablishment(?Establishment $establishment): self
    {
        $this->establishment = $establishment;

        return $this;
    }

    public function getSubDivision(): ?SubDivision
    {
        return $this->subDivision;
    }

    public function setSubDivision(?SubDivision $subDivision): self
    {
        $this->subDivision = $subDivision;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

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
            $movement->addCorrespondent($this);
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        if ($this->movements->removeElement($movement)) {
            $movement->removeCorrespondent($this);
        }

        return $this;
    }
}
