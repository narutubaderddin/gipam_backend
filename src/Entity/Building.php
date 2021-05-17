<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\BuildingRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BuildingRepository::class)
 * @ORM\Table(name="batiment")
 */
class Building
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
     * @JMS\Groups("building","short")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @JMS\Groups("building")
     *
     * @ORM\Column(name="addresse", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @JMS\Groups("building")
     *
     * @ORM\Column(name="distrib", type="string", length=255, nullable=true)
     */
    private $distrib;

    /**
     * @JMS\Groups("building")
     *
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("building")
     *
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate;

    /**
     * @JMS\Groups("building")
     *
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="buildings")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     */
    private $site;

    /**
     * @JMS\Groups("building")
     *
     * @ORM\ManyToOne(targetEntity=Commune::class, inversedBy="buildings")
     * @ORM\JoinColumn(name="commune_id", referencedColumnName="id")
     */
    private $commune;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="building")
     */
    private $rooms;

    /**
     * @JMS\Exclude()
     *
     * @ORM\ManyToMany(targetEntity=Responsible::class, mappedBy="buildings")
     */
    private $responsibles;

    /**
     * @JMS\Groups("building")
     *
     * @ORM\Column(name="cedex", type="string", length=255, nullable=true)
     */
    private $cedex;


    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->responsibles = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDistrib(): ?string
    {
        return $this->distrib;
    }

    public function setDistrib(?string $distrib): self
    {
        $this->distrib = $distrib;

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

    public function getDisappearanceDate(): ?DateTimeInterface
    {
        return $this->disappearanceDate;
    }

    public function setDisappearanceDate(?DateTimeInterface $disappearanceDate): self
    {
        $this->disappearanceDate = $disappearanceDate;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->commune;
    }

    public function setCommune(?Commune $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setBuilding($this);
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getBuilding() === $this) {
                $room->setBuilding(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Responsible[]
     */
    public function getResponsibles(): Collection
    {
        return $this->responsibles;
    }

    public function addResponsible(Responsible $responsible): self
    {
        if (!$this->responsibles->contains($responsible)) {
            $this->responsibles[] = $responsible;
            $responsible->addBuilding($this);
        }

        return $this;
    }

    public function removeResponsible(Responsible $responsible): self
    {
        if ($this->responsibles->removeElement($responsible)) {
            $responsible->removeBuilding($this);
        }

        return $this;
    }

    public function getCedex(): ?string
    {
        return $this->cedex;
    }

    public function setCedex(?string $cedex): self
    {
        $this->cedex = $cedex;

        return $this;
    }

}
