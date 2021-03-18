<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements userInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"user", "users","campaignCollabAssignement"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Serializer\Groups({"user", "users"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=50)
     * @Serializer\Groups({"user"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     * @Serializer\Groups({"user"})
     */
    private $lastName;


    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Serializer\Groups({"user"})
     */
    private $password;


    /**
     * @ORM\Column(type="array", nullable=true)
     * @Serializer\Groups({"user", "users"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity=ArtWorkLog::class, mappedBy="user")
     */
    private $artWorkLogs;

    /**
     * @ORM\ManyToOne(targetEntity=Ministry::class, inversedBy="users")
     */
    private $ministry;

    public function __construct()
    {
        $this->roles = ['ROLE_COLLABORATOR'];
        $this->artWorkLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_COLLABORATOR';
        return array_unique($roles);
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password ;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function removeRole($role)
    {
        if (in_array($role, $this->roles)) {
            unset($this->roles[array_search($role, $this->roles)]);
        }
        array_splice($this->roles, 1, 1);

        return $this;
    }
    /**
     * @param string $role
     *
     * @return User
     */
    public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            array_unshift($this->roles, $role);
        }
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

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection|ArtWorkLog[]
     */
    public function getArtWorkLogs(): Collection
    {
        return $this->artWorkLogs;
    }

    public function addArtWorkLog(ArtWorkLog $artWorkLog): self
    {
        if (!$this->artWorkLogs->contains($artWorkLog)) {
            $this->artWorkLogs[] = $artWorkLog;
            $artWorkLog->setUser($this);
        }

        return $this;
    }

    public function removeArtWorkLog(ArtWorkLog $artWorkLog): self
    {
        if ($this->artWorkLogs->removeElement($artWorkLog)) {
            // set the owning side to null (unless already changed)
            if ($artWorkLog->getUser() === $this) {
                $artWorkLog->setUser(null);
            }
        }

        return $this;
    }

    public function getMinistry(): ?Ministry
    {
        return $this->ministry;
    }

    public function setMinistry(?Ministry $ministry): self
    {
        $this->ministry = $ministry;

        return $this;
    }
}
