<?php

namespace App\Entity;

use App\Repository\TypeDeposantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeDeposantRepository::class)
 */
class TypeDeposant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Deposant::class, mappedBy="typeDeposant")
     */
    private $deposants;

    public function __construct()
    {
        $this->deposants = new ArrayCollection();
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
     * @return Collection|Deposant[]
     */
    public function getDeposants(): Collection
    {
        return $this->deposants;
    }

    public function addDeposant(Deposant $deposant): self
    {
        if (!$this->deposants->contains($deposant)) {
            $this->deposants[] = $deposant;
            $deposant->setTypeDeposant($this);
        }

        return $this;
    }

    public function removeDeposant(Deposant $deposant): self
    {
        if ($this->deposants->removeElement($deposant)) {
            // set the owning side to null (unless already changed)
            if ($deposant->getTypeDeposant() === $this) {
                $deposant->setTypeDeposant(null);
            }
        }

        return $this;
    }
}
