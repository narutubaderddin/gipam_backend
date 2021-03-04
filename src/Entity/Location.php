<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=LocationType::class)
     */
    private $locationType;

    /**
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="locations")
     */
    private $establishment;

    /**
     * @ORM\ManyToOne(targetEntity=SubDivision::class, inversedBy="locations")
     */
    private $subDivision;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocationType(): ?LocationType
    {
        return $this->locationType;
    }

    public function setLocationType(?LocationType $locationType): self
    {
        $this->locationType = $locationType;

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
}
