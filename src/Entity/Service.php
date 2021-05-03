<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(name="service")
 */
class Service
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("id", "service", "service_id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("service")
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Groups("service")
     *
     * @ORM\Column(name="sigle", type="string", length=255, nullable=true)
     */
    private $acronym;

    /**
     * @JMS\Groups("service")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("service")
     *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate;

    /**
     * @ORM\OneToMany(targetEntity=Correspondent::class, mappedBy="service")
     */
    private $correspondents;

    /**
     * @JMS\Groups("service", "sub_division_id")
     *
     * @ORM\ManyToOne(targetEntity=SubDivision::class, inversedBy="services")
     * @ORM\JoinColumn(name="sous_direction_id", referencedColumnName="id")
     */
    private $subDivision;

    public function __construct()
    {
        $this->correspondents = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getAcronym()
    {
        return $this->acronym;
    }

    /**
     * @param mixed $acronym
     */
    public function setAcronym($acronym): void
    {
        $this->acronym = $acronym;
    }

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
    public function getDisappearanceDate()
    {
        return $this->disappearanceDate;
    }

    /**
     * @param mixed $disappearanceDate
     */
    public function setDisappearanceDate($disappearanceDate): void
    {
        $this->disappearanceDate = $disappearanceDate;
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $correspondent->setService($this);
        }

        return $this;
    }

    public function removeCorrespondent(Correspondent $correspondent): self
    {
        if ($this->correspondents->removeElement($correspondent)) {
            // set the owning side to null (unless already changed)
            if ($correspondent->getService() === $this) {
                $correspondent->setService(null);
            }
        }

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
