<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\EraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=EraRepository::class)
 * @ORM\Table(name="epoque")
 */
class Era
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("id", "era")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups({"era","era_furniture"})
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="era")
     */
    private $furniture;

    /**
     * @JMS\Groups("era")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
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
     * @return Collection|Furniture[]
     */
    public function getFurniture(): Collection
    {
        return $this->furniture;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furniture->contains($furniture)) {
            $this->furniture[] = $furniture;
            $furniture->setEra($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getEra() === $this) {
                $furniture->setEra(null);
            }
        }

        return $this;
    }
}
