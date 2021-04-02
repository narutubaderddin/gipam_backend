<?php

namespace App\Entity;

use App\Repository\EpoqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpoqueRepository::class)
 */
class Epoque
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
     * @ORM\OneToMany(targetEntity=ObjetMobilier::class, mappedBy="era")
     */
    private $objetMobiliers;

    public function __construct()
    {
        $this->objetMobiliers = new ArrayCollection();
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
     * @return Collection|ObjetMobilier[]
     */
    public function getObjetMobiliers(): Collection
    {
        return $this->objetMobiliers;
    }

    public function addobjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if (!$this->objetMobiliers->contains($objetMobilier)) {
            $this->objetMobiliers[] = $objetMobilier;
            $objetMobilier->setEpoque($this);
        }

        return $this;
    }

    public function removeobjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if ($this->objetMobiliers->removeElement($objetMobilier)) {
            // set the owning side to null (unless already changed)
            if ($objetMobilier->getEpoque() === $this) {
                $objetMobilier->setEpoque(null);
            }
        }

        return $this;
    }
}
