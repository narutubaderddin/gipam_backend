<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\EstablishmentRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=EstablishmentRepository::class)
 * @ORM\Table(name="etablissement")
 * @UniqueEntity("label", repositoryMethod="iFindBy", message="Un établissement avec ce libellé existe déjà!")
 * @UniqueEntity("acronym", repositoryMethod="iFindBy", message="Un établissement avec ce sigle existe déjà!")
 */
class Establishment
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("id", "establishment", "establishment_id", "short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("establishment", "short", "request_list", "request_details")
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Groups("establishment","movement_list")
     *
     * @ORM\Column(name="sigle", type="string", length=255, nullable=true)
     */
    private $acronym;

    /**
     * @JMS\Groups("establishment")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("establishment")
     *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="establishment")
     */
    private $locations;

    /**
     * @JMS\Groups("establishment", "ministry_id","movement_list")
     *
     * @ORM\ManyToOne(targetEntity=Ministry::class, inversedBy="establishments")
     * @ORM\JoinColumn(name="ministere_id", referencedColumnName="id")
     */
    private $ministry;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Correspondent::class, mappedBy="establishment")
     */
    private $correspondents;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=SubDivision::class, mappedBy="establishment")
     */
    private $subDivisions;

    /**
     * @JMS\Groups("establishment", "establishment_type_id")
     *
     * @ORM\ManyToOne(targetEntity=EstablishmentType::class, inversedBy="establishments")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->correspondents = new ArrayCollection();
        $this->subDivisions = new ArrayCollection();
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

    public function getDisappearanceDate(): ?DateTimeInterface
    {
        return $this->disappearanceDate;
    }

    public function setDisappearanceDate(?DateTimeInterface $disappearanceDate): self
    {
        $this->disappearanceDate = $disappearanceDate;

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
            $location->setEstablishment($this);
        }

        return $this;
    }

    public function removeLocation(location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getEstablishment() === $this) {
                $location->setEstablishment(null);
            }
        }

        return $this;
    }

    public function getMinistry(): ?Ministry
    {
        return $this->ministry;
    }

    public function setMinistry(?Ministry $ministry): self
    {
        $this->ministry = $ministry;

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
            $correspondent->setEstablishment($this);
        }

        return $this;
    }

    public function removeCorrespondent(Correspondent $correspondent): self
    {
        if ($this->correspondents->removeElement($correspondent)) {
            // set the owning side to null (unless already changed)
            if ($correspondent->getEstablishment() === $this) {
                $correspondent->setEstablishment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubDivision[]
     */
    public function getSubDivisions(): Collection
    {
        return $this->subDivisions;
    }

    public function addSubDivision(SubDivision $subDivision): self
    {
        if (!$this->subDivisions->contains($subDivision)) {
            $this->subDivisions[] = $subDivision;
            $subDivision->setEstablishment($this);
        }

        return $this;
    }

    public function removeSubDivision(SubDivision $subDivision): self
    {
        if ($this->subDivisions->removeElement($subDivision)) {
            // set the owning side to null (unless already changed)
            if ($subDivision->getEstablishment() === $this) {
                $subDivision->setEstablishment(null);
            }
        }

        return $this;
    }

    public function getType(): ?EstablishmentType
    {
        return $this->type;
    }

    public function setType(?EstablishmentType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
