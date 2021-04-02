<?php

namespace App\Entity;

use App\Repository\TypeLocalisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeLocalisationRepository::class)
 */
class TypeLocalisation
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
     * @ORM\OneToMany(targetEntity=Localisation::class, mappedBy="type")
     */
    private $localisations;

    public function __construct()
    {
        $this->localisations = new ArrayCollection();
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
     * @return Collection|Localisation[]
     */
    public function getLocalisations(): Collection
    {
        return $this->localisations;
    }

    public function addLocalisation(Localisation $localisation): self
    {
        if (!$this->localisations->contains($localisation)) {
            $this->localisations[] = $localisation;
            $localisation->setType($this);
        }

        return $this;
    }

    public function removeLocalisation(Localisation $localisation): self
    {
        if ($this->localisations->removeElement($localisation)) {
            // set the owning side to null (unless already changed)
            if ($localisation->getType() === $this) {
                $localisation->setType(null);
            }
        }

        return $this;
    }
}
