<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 */
class Author
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\OneToMany(targetEntity=AuthorType::class, mappedBy="author")
     */
    private $types;

    /**
     * @ORM\ManyToMany(targetEntity=Furniture::class, mappedBy="authors")
     */
    private $furnitures;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->furnitures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|AuthorType[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(AuthorType $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->setAuthor($this);
        }

        return $this;
    }

    public function removeType(AuthorType $type): self
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getAuthor() === $this) {
                $type->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Furniture[]
     */
    public function getFurnitures(): Collection
    {
        return $this->furnitures;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furnitures->contains($furniture)) {
            $this->furnitures[] = $furniture;
            $furniture->addAuthor($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furnitures->removeElement($furniture)) {
            $furniture->removeAuthor($this);
        }

        return $this;
    }
}
