<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AuthorRepository::class)
 * @ORM\Table(name="auteur")
 * @UniqueEntity(fields={"firstName", "lastName"}, repositoryMethod="iFindBy", message="Un auteur avec ce nom et prénom existe déjà!")
 */
class Author
{
    use TimestampableEntity;

    /**
     * @JMS\Groups({"id","furniture_author","artwork", "short"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups({"furniture_author","authors","request_list","art_work_list","art_work_details", "short"})
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @JMS\Groups({"furniture_author","authors","request_list","art_work_list","art_work_details", "short"})
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     *
     * @ORM\ManyToMany(targetEntity=Furniture::class, mappedBy="authors")
     */
    private $furniture;

    /**
     * @JMS\Groups({"authors"})
     *
     * @ORM\ManyToOne(targetEntity=AuthorType::class, inversedBy="authors")
     */
    private $type;

    /**
     * @JMS\Groups({"authors"})
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="author")
     */
    private $people;

    public function __construct()
    {
        $this->furniture = new ArrayCollection();
        $this->people = new ArrayCollection();
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

    /**
     * @return string|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("label")
     * @JMS\Groups("authors","furniture_author", "short")
     */
    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->setAuthor($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getAuthor() === $this) {
                $person->setAuthor(null);
            }
        }

        return $this;
    }
}
