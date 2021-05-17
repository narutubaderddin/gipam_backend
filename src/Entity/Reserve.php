<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ReserveRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReserveRepository::class)
 * @ORM\Table(name="reserve")
 * @UniqueEntity("label", repositoryMethod="iFindBy", message="Une entité avec ce libellé existe déjà!")
 * @UniqueEntity("room", message="Une entité avec cette pièce existe déjà!")
 */
class Reserve
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @JMS\Groups("short", "id")
     */
    private $id;

    /**
     * @ORM\Column(name="reference", type="string", length=255)
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups("short", "reserve")
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="reserves")
     *
     * @ORM\JoinColumn(name="piece_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups("reserve")
     */
    private $room;

    /**
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups("reserve")
     */
    private $startDate;

    /**
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     *
     * @JMS\Groups("reserve")
     */
    private $endDate;

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    /**
     * @return Building|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("building")
     * @JMS\Groups("reserve")
     */
    public function getBuilding(): ?Building
    {
        return $this->getRoom() ? $this->getRoom()->getBuilding() : null;
    }

    /**
     * @return Site|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("site")
     * @JMS\Groups("reserve")
     */
    public function getSite(): ?Site
    {
        return $this->getBuilding() ? $this->getBuilding()->getSite() : null;
    }

    /**
     * @return Commune|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("commune")
     * @JMS\Groups("reserve")
     */
    public function getCommune(): ?Commune
    {
        return $this->getBuilding() ? $this->getBuilding()->getCommune() : null;
    }

    /**
     * @return Department|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("department")
     * @JMS\Groups("reserve")
     */
    public function getDepartment(): ?Department
    {
        return $this->getCommune() ? $this->getCommune()->getDepartment() : null;
    }

    /**
     * @return Region|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("region")
     * @JMS\Groups("reserve")
     */
    public function getRegion(): ?Region
    {
        return $this->getDepartment() ? $this->getDepartment()->getRegion() : null;
    }
}
