<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\ManyToMany;
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

    public function __construct()
    {
        $this->roles = ['ROLE_COLLABORATOR'];
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
        if(in_array($role, $this->roles)){
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
        if(!in_array($role, $this->roles)){
            array_unshift($this->roles, $role);

        }
        return $this;
    }
}
