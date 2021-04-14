<?php

namespace App\Entity;

use App\Repository\AuthorTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorTypeRepository::class)
 * @ORM\Table(name="type_auteur")
 */
class AuthorType
{
    public const LABEL = [
        'patronyme' => 'Patronyme',
        'pseudo' => 'pseudo',
        'dapres' => 'd’après',
        'attribuea' => 'attribué à',
        'nonIdentifie' => 'non identifié',
        'editeur' => 'éditeur',
        'imprimeur' => 'imprimeur',
        'fabricant' => 'fabricant',
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=Author::class, mappedBy="type")
     */
    private $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
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
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->setType($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getType() === $this) {
                $author->setType(null);
            }
        }

        return $this;
    }
}
