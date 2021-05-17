<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\CorrespondentRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CorrespondentRepository::class)
 * @ORM\Table(name="correspondant")
 */
class Correspondent
{
    use TimestampableEntity;

    /**
     * @JMS\Groups("id","short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="correspondents")
     * @ORM\JoinColumn(name="etablissement_id", referencedColumnName="id")
     */
    private $establishment;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\ManyToMany(targetEntity=Movement::class, mappedBy="correspondents")
     */
    private $movements;


    /**
     * @JMS\Groups("correspondent")
     * @ORM\Column(name="fonction", type="string", length=255, nullable=true)
     */
    private $function;

    /**
     * @JMS\Groups("correspondent")
     * @ORM\Column(name="connexion",type="string", length=255, nullable=true)
     */
    private $login;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\ManyToMany(targetEntity=SubDivision::class, inversedBy="correspondents")
     * @ORM\JoinTable(name="correspondant_sous_direction",
     *      joinColumns={@ORM\JoinColumn(name="correspondant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="sous_direction_id", referencedColumnName="id")}
     *      )
     */
    private $subDivisions;

    /**
     * @JMS\Groups("correspondent")
     *
     * @ORM\ManyToMany(targetEntity=Service::class, inversedBy="correspondents")
     * @ORM\JoinTable(name="correspondant_service",
     *      joinColumns={@ORM\JoinColumn(name="correspondant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="service_id", referencedColumnName="id")}
     *      )
     */
    private $services;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
        $this->subDivisions = new ArrayCollection();
        $this->services = new ArrayCollection();
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



    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(?string $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("label")
     * @JMS\Groups("short")
     */
    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return Collection|SubDivision[]
     */
    public function getSubDivisions(): Collection
    {
        return $this->subDivisions;
    }

    public function addSubDivision(SubDivision $subDivision): self
    {
        if (!$this->subDivisions->contains($subDivision)) {
            $this->subDivisions[] = $subDivision;
        }

        return $this;
    }

    public function removeSubDivision(SubDivision $subDivision): self
    {
        $this->subDivisions->removeElement($subDivision);

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
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        $this->services->removeElement($service);

        return $this;
    }
}
