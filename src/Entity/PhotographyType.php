<?php

namespace App\Entity;

use App\Repository\PhotographyTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotographyTypeRepository::class)
 * @ORM\Table(name="type_photographie")
 */
class PhotographyType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\OneToMany(targetEntity=Photography::class, mappedBy="photographyType")
     */
    private $photographies;

    public function __construct()
    {
        $this->photographies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * @return Collection|Photography[]
     */
    public function getPhotographies(): Collection
    {
        return $this->photographies;
    }

    public function addPhotography(Photography $photography): self
    {
        if (!$this->photographies->contains($photography)) {
            $this->photographies[] = $photography;
            $photography->setPhotographyType($this);
        }

        return $this;
    }

    public function removePhotography(Photography $photography): self
    {
        if ($this->photographies->removeElement($photography)) {
            // set the owning side to null (unless already changed)
            if ($photography->getPhotographyType() === $this) {
                $photography->setPhotographyType(null);
            }
        }

        return $this;
    }
}
