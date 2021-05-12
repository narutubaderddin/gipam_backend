<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\RoomRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 * @ORM\Table(name="piece")
 * @UniqueEntity("reference", repositoryMethod="iFindBy", message="Une pièce avec cette référence existe déjà!")
 */
class Room
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Groups("id","short")
     */
    private $id;

    /**
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups("short", "room")
     */
    private $reference;

    /**
     * @ORM\Column(name="niveau", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups("room")
     */
    private $level;

    /**
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups("room")
     */
    private $startDate;

    /**
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     *
     * @JMS\Groups("room")
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="rooms")
     * @ORM\JoinColumn(name="batiment_id", referencedColumnName="id")
     *
     * @JMS\Groups("room")
     */
    private $building;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="room")
     */
    private $locations;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Reserve::class, mappedBy="room", orphanRemoval=true)
     */
    private $reserves;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->reserves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): self
    {
        $this->level = $level;

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

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setRoom($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getRoom() === $this) {
                $location->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reserve[]
     */
    public function getReserves(): Collection
    {
        return $this->reserves;
    }

    public function addReserf(Reserve $reserf): self
    {
        if (!$this->reserves->contains($reserf)) {
            $this->reserves[] = $reserf;
            $reserf->setRoom($this);
        }

        return $this;
    }

    public function removeReserf(Reserve $reserf): self
    {
        if ($this->reserves->removeElement($reserf)) {
            // set the owning side to null (unless already changed)
            if ($reserf->getRoom() === $this) {
                $reserf->setRoom(null);
            }
        }

        return $this;
    }
}
