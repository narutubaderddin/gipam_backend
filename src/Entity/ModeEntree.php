<?php

namespace App\Entity;

use App\Repository\ModeEntreeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModeEntreeRepository::class)
 */
class ModeEntree
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=StatutPropriete::class, mappedBy="modeEntree")
     */
    private $statutProprietes;

    public function __construct()
    {
        $this->statutProprietes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|StatutPropriete[]
     */
    public function getStatutProprietes(): Collection
    {
        return $this->statutProprietes;
    }

    public function addStatutPropriete(StatutPropriete $statutPropriete): self
    {
        if (!$this->statutProprietes->contains($statutPropriete)) {
            $this->statutProprietes[] = $statutPropriete;
            $statutPropriete->setModeEntree($this);
        }

        return $this;
    }

    public function removeStatutPropriete(StatutPropriete $statutPropriete): self
    {
        if ($this->statutProprietes->removeElement($statutPropriete)) {
            // set the owning side to null (unless already changed)
            if ($statutPropriete->getModeEntree() === $this) {
                $statutPropriete->setModeEntree(null);
            }
        }

        return $this;
    }
}
