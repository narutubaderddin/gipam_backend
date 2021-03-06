<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\DepartmentRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 * @ORM\Table(name="departement")
 * @UniqueEntity("name", repositoryMethod="iFindBy", message="Un département avec ce nom existe déjà!")
 */
class Department
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
     * @JMS\Groups("department","short")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @JMS\Groups("department")
     *
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("department")
     *
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate;

    /**
     * @JMS\Groups("department")
     *
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="departments")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    private $region;

    /**
     * @JMS\Exclude
     *
     * @ORM\OneToMany(targetEntity=Commune::class, mappedBy="department")
     */
    private $communes;

    /**
     * @JMS\Exclude()
     *
     * @ORM\ManyToMany(targetEntity=Responsible::class, mappedBy="departments")
     */
    private $responsibles;

    public function __construct()
    {
        $this->communes = new ArrayCollection();
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|Commune[]
     */
    public function getCommunes(): Collection
    {
        return $this->communes;
    }

    public function addCommune(Commune $commune): self
    {
        if (!$this->communes->contains($commune)) {
            $this->communes[] = $commune;
            $commune->setDepartment($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): self
    {
        if ($this->communes->removeElement($commune)) {
            // set the owning side to null (unless already changed)
            if ($commune->getDepartment() === $this) {
                $commune->setDepartment(null);
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
            $responsible->addDepartment($this);
        }

        return $this;
    }

    public function removeResponsible(Responsible $responsible): self
    {
        if ($this->responsibles->removeElement($responsible)) {
            $responsible->removeDepartment($this);
        }

        return $this;
    }
}
