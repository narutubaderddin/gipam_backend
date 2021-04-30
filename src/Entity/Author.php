<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ORM\Table(name="auteur")
 */
class Author
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\ManyToMany(targetEntity=Furniture::class, mappedBy="authors")
     */
    private $furniture;

    /**
     * @ORM\ManyToOne(targetEntity=AuthorType::class, inversedBy="authors")
     */
    private $type;

    /**
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
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
            $furniture->addAuthor($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            $furniture->removeAuthor($this);
        }

        return $this;
    }

    public function getType(): ?AuthorType
    {
        return $this->type;
    }

    public function setType(?AuthorType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
