<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="region")
 * @UniqueEntity("name", repositoryMethod="iFindBy", message="Une région avec ce nom existe déjà!")
 */
class Region
{
    use TimestampableEntity;

    /**
     * @JMS\Groups("id", "short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("region", "short")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @JMS\Groups("region")
     *
     * @Assert\NotBlank
     * @Assert\Type("\DateTimeInterface")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @JMS\Groups("region")
     *
     * @Assert\Type("\DateTimeInterface")  *
     * @ORM\Column(name="date_disparition", type="datetime", nullable=true)
     */
    private $disappearanceDate = null;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Department::class, mappedBy="region")
     */
    private $departments;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Responsible::class, mappedBy="region")
     */
    private $responsibles;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
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

    public function getstartDate(): ?\DateTimeInterface
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

    /**
     * @return Collection|Department[]
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments[] = $department;
            $department->setRegion($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getRegion() === $this) {
                $department->setRegion(null);
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
            $responsible->setRegion($this);
        }

        return $this;
    }

    public function removeResponsible(Responsible $responsible): self
    {
        if ($this->responsibles->removeElement($responsible)) {
            // set the owning side to null (unless already changed)
            if ($responsible->getRegion() === $this) {
                $responsible->setRegion(null);
            }
        }

        return $this;
    }
}
