<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\LocationTypeRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LocationTypeRepository::class)
 * @ORM\Table(name="type_localisation")
 */
class LocationType
{
    use TimestampableEntity;
    public const LABEL = [
        'bureau' => 'Bureau',
        'salleReunion' => 'Salle de réunion',
        'antichambre' => 'Antichambre – salle d’attente',
        'palier' => 'Palier',
        'couloir' => 'Couloir',
        'hall' => 'Hall',
        'exterieur' => 'Extérieur',
    ];

    /**
     * @JMS\Groups("id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("location_type")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Exclude
     *
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="type")
     */
    private $locations;

    /**
     * @JMS\Groups("location_type")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("location_type")
     *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
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
            $location->setType($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getType() === $this) {
                $location->setType(null);
            }
        }

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
}
