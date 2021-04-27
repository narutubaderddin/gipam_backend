<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ResponsibleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponsibleRepository::class)
 * @ORM\Table(name="responsable")
 */
class Responsible
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $firstName;

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
     * @ORM\ManyToMany(targetEntity=Building::class, inversedBy="responsibles")
     * @ORM\JoinTable(name="responsable_batiment",
     *      joinColumns={@ORM\JoinColumn(name="responsable_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="batiment_id", referencedColumnName="id")}
     *      )
     */
    private $buildings;

    public function __construct()
    {
        $this->buildings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|Building[]
     */
    public function getBuildings(): Collection
    {
        return $this->buildings;
    }

    public function addBuilding(Building $building): self
    {
        if (!$this->buildings->contains($building)) {
            $this->buildings[] = $building;
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        $this->buildings->removeElement($building);

        return $this;
    }
}
