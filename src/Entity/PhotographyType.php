<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
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
    use TimestampableEntity;
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
