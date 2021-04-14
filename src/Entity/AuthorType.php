<?php

namespace App\Entity;

use App\Repository\AuthorTypeRepository;
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
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="types")
     * @ORM\JoinColumn(name="auteur_id", referencedColumnName="id")
     */
    private $author;

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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
