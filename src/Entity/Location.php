<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 * @ORM\Table(name="localisation")
 */
class Location
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("movement_list")
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="locations")
     * @ORM\JoinColumn(name="etablissement_id", referencedColumnName="id")
     */
    private $establishment;

    /**
     * @JMS\Groups("movement_list")
     * @ORM\ManyToOne(targetEntity=SubDivision::class, inversedBy="locations")
     * @ORM\JoinColumn(name="sous_direction_id", referencedColumnName="id")
     */
    private $subDivision;

    /**
     * @ORM\OneToMany(targetEntity=Movement::class, mappedBy="location")
     */
    private $movements;

    /**
     * @ORM\ManyToOne(targetEntity=LocationType::class, inversedBy="locations")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @JMS\Groups("movement_list")
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="locations")
     * @ORM\JoinColumn(name="piece_id", referencedColumnName="id")
     */
    private $room;

    public function __construct()
    {
        $this->movements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $movement->setLocation($this);
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        if ($this->movements->removeElement($movement)) {
            // set the owning side to null (unless already changed)
            if ($movement->getLocation() === $this) {
                $movement->setLocation(null);
            }
        }

        return $this;
    }

    public function getType(): ?LocationType
    {
        return $this->type;
    }

    public function setType(?LocationType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }
}
