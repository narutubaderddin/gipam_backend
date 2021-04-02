<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    public const LIBELLE = [
        'bienRemarquable' => 'Bien Patrimonial remarquable',
        'bienStandard' => 'Bien Patrimonial standard',
        'bienUsuel' => 'Bien usuel',
    ];
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
     * @ORM\OneToMany(targetEntity=StatutPropriete::class, mappedBy="category")
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
            $statutPropriete->setCategorie($this);
        }

        return $this;
    }

    public function removeStatutPropriete(StatutPropriete $statutPropriete): self
    {
        if ($this->statutProprietes->removeElement($statutPropriete)) {
            // set the owning side to null (unless already changed)
            if ($statutPropriete->getCategorie() === $this) {
                $statutPropriete->setCategorie(null);
            }
        }

        return $this;
    }
}
