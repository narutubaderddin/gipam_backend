<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ArtWorkLogRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ArtWorkLogRepository::class)
 * @ORM\Table(name="log_oeuvre")
 */
class ArtWorkLog
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="artWorkLog")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")
     */
    private $furniture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }



    public function getFurniture(): ?Furniture
    {
        return $this->furniture;
    }

    public function setFurniture(?Furniture $furniture): self
    {
        $this->furniture = $furniture;

        return $this;
    }
}
