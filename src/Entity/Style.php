<?php

namespace App\Entity;

use App\Repository\StyleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StyleRepository::class)
 */
class Style
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
     * @ORM\OneToMany(targetEntity=ObjetMobilier::class, mappedBy="style")
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

    public function addObjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if (!$this->objetMobiliers->contains($objetMobilier)) {
            $this->objetMobiliers[] = $objetMobilier;
            $objetMobilier->setStyle($this);
        }

        return $this;
    }

    public function removeObjetMobilier(ObjetMobilier $objetMobilier): self
    {
        if ($this->objetMobiliers->removeElement($objetMobilier)) {
            // set the owning side to null (unless already changed)
            if ($objetMobilier->getStyle() === $this) {
                $objetMobilier->setStyle(null);
            }
        }

        return $this;
    }
}
