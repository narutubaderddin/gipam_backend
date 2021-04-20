<?php

namespace App\Entity;

use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommuneRepository::class)
 * @ORM\Table(name="commune")
 */
class Commune
{
    /**
     * @JMS\Groups("id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("commune")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @JMS\Groups("commune")
     *
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="communes")
     * @ORM\JoinColumn(name="departement_id", referencedColumnName="id")
     */
    private $departement;

    /**
     * @JMS\Exclude
     *
     * @ORM\OneToMany(targetEntity=Building::class, mappedBy="commune")
     */
    private $buildings;

    /**
     * @JMS\Groups("commune")
     *
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("commune")
     *
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate;

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

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

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
            $building->setCommune($this);
        }

        return $this;
    }

    public function removeBuilding(Building $building): self
    {
        if ($this->buildings->removeElement($building)) {
            // set the owning side to null (unless already changed)
            if ($building->getCommune() === $this) {
                $building->setCommune(null);
            }
        }

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

    public function getDisappearanceDate(): ?\DateTimeInterface
    {
        return $this->disappearanceDate;
    }

    public function setDisappearanceDate(?\DateTimeInterface $disappearanceDate): self
    {
        $this->disappearanceDate = $disappearanceDate;

        return $this;
    }
}
