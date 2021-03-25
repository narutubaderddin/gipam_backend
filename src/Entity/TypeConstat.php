<?php

namespace App\Entity;

use App\Repository\TypeConstatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeConstatRepository::class)
 */
class TypeConstat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=SousTypeConstat::class, mappedBy="typeConstat")
     */
    private $sousTypesConstat;

    public function __construct()
    {
        $this->sousTypesConstat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|SousTypeConstat[]
     */
    public function getSousTypesConstat(): Collection
    {
        return $this->sousTypesConstat;
    }

    public function addSousTypesConstat(SousTypeConstat $sousTypeConstat): self
    {
        if (!$this->sousTypesConstat->contains($sousTypeConstat)) {
            $this->sousTypesConstat[] = $sousTypeConstat;
            $sousTypeConstat->setTypeConstat($this);
        }

        return $this;
    }

    public function removeSousTypesConstat(SousTypeConstat $sousTypeConstat): self
    {
        if ($this->sousTypesConstat->removeElement($sousTypeConstat)) {
            // set the owning side to null (unless already changed)
            if ($sousTypeConstat->getTypeConstat() === $this) {
                $sousTypeConstat->setTypeConstat(null);
            }
        }

        return $this;
    }
}
