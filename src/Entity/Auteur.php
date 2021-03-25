<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuteurRepository::class)
 */
class Auteur
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
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=TypeAuteur::class, mappedBy="auteur")
     */
    private $types;

    /**
     * @ORM\ManyToMany(targetEntity=Furniture::class, mappedBy="auteurs")
     */
    private $furniture;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|TypeAuteur[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(TypeAuteur $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->setAuteur($this);
        }

        return $this;
    }

    public function removeType(TypeAuteur $type): self
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getAuteur() === $this) {
                $type->setAuteur(null);
            }
        }

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
            $furniture->addAuteur($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            $furniture->removeAuteur($this);
        }

        return $this;
    }
}
