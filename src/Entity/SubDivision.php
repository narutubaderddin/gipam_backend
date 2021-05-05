<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\SubDivisionRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=SubDivisionRepository::class)
 * @ORM\Table(name="sous_direction")
 */
class SubDivision
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("id", "sub_division", "sub_division_id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("sub_division")
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Groups("sub_division")
     *
     * @ORM\Column(name="sigle", type="string", length=255, nullable=true)
     */
    private $acronym;

    /**
     * @JMS\Groups("sub_division")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("sub_division")
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="subDivision")
     */
    private $locations;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Correspondent::class, mappedBy="subDivision")
     */
    private $correspondents;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="subDivision")
     */
    private $services;

    /**
     * @JMS\Groups("sub_division", "establishment_id")
     *
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="subDivisions")
     * @ORM\JoinColumn(name="etablissement_id", referencedColumnName="id")
     */
    private $establishment;

    /**
     * @ORM\OneToMany(targetEntity=Request::class, mappedBy="subDivision")
     */
    private $requests;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->correspondents = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->requests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): self
    {
        $this->acronym = $acronym;

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

    /**
     * @return Collection|location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setSubDivision($this);
        }

        return $this;
    }

    public function removeLocation(location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getSubDivision() === $this) {
                $location->setSubDivision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Correspondent[]
     */
    public function getCorrespondents(): Collection
    {
        return $this->correspondents;
    }

    public function addCorrespondent(Correspondent $correspondent): self
    {
        if (!$this->correspondents->contains($correspondent)) {
            $this->correspondents[] = $correspondent;
            $correspondent->setSubDivision($this);
        }

        return $this;
    }

    public function removeCorrespondent(Correspondent $correspondent): self
    {
        if ($this->correspondents->removeElement($correspondent)) {
            // set the owning side to null (unless already changed)
            if ($correspondent->getSubDivision() === $this) {
                $correspondent->setSubDivision(null);
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
            $service->setSubDivision($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getSubDivision() === $this) {
                $service->setSubDivision(null);
            }
        }

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
     * @return Collection|Request[]
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests[] = $request;
            $request->setSubDivision($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getSubDivision() === $this) {
                $request->setSubDivision(null);
            }
        }

        return $this;
    }
}
