<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 * @ORM\Table(name="personne")
 * @UniqueEntity("email", repositoryMethod="iFindBy", message="Une personne avec ce courriel existe déjà!")
 */
class Person
{
    use TimestampableEntity;

    /**
     * @JMS\Groups("id", "short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("person")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @JMS\Groups("person")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @JMS\Groups("person")
     *
     * @Assert\Regex(
     *     pattern="/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.0-9]*$/",
     *     message="This is not a valid phone number"
     * )
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @JMS\Groups("person")
     *
     * @Assert\Email()
     *
     * @ORM\Column(name="courriel", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @JMS\Groups("person")
     *
     * @Assert\Regex(
     *     pattern="/^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$/",
     *     message="This is not a valid website"
     * )
     *
     * @ORM\Column(name="site_web", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @JMS\Groups("person")
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $comment;

    /**
     * @JMS\Groups("person")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * @JMS\Groups("person")
     *
     * @JMS\MaxDepth(depth=1)
     *
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="people")
     * @ORM\JoinColumn(name="auteur_id", referencedColumnName="id")
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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

    /**
     * @return string|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("label")
     * @JMS\Groups("short")
     */
    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
