<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="region")
 */
class Region
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("region")
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

    public function __construct()
    {
        $this->departments = new ArrayCollection();
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
}
