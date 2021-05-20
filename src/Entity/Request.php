<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=RequestRepository::class)
 * @ORM\Table(name="demande")
 */
class Request
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="numeroPiece",type="string", length=50, nullable=true)
     */
    private $pieceNumber;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="fonction",type="string", length=255, nullable=true)
     */
    private $function;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $comment;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="telephone",type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $level;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(name="status_demande",type="string", length=50, nullable=true)
     */
    private $requestStatus;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subDivision;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $establishement;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $building;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phoneApplicant;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstNameApplicant;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastNameApplicant;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\OneToMany(targetEntity=RequestedArtWorks::class, mappedBy="request")
     */
    private $requestedArtWorks;

    public function __construct()
    {
        $this->requestedArtWorks = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPieceNumber(): ?string
    {
        return $this->pieceNumber;
    }

    public function setPieceNumber(?string $pieceNumber): self
    {
        $this->pieceNumber = $pieceNumber;

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




    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(?string $function): self
    {
        $this->function = $function;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(?string $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getRequestStatus(): ?string
    {
        return $this->requestStatus;
    }

    public function setRequestStatus(?string $requestStatus): self
    {
        $this->requestStatus = $requestStatus;

        return $this;
    }

    public function getSubDivision(): ?string
    {
        return $this->subDivision;
    }

    public function setSubDivision(?string $subDivision): self
    {
        $this->subDivision = $subDivision;

        return $this;
    }

    public function getEstablishement(): ?string
    {
        return $this->establishement;
    }

    public function setEstablishement(?string $establishement): self
    {
        $this->establishement = $establishement;

        return $this;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getPhoneApplicant(): ?string
    {
        return $this->phoneApplicant;
    }

    public function setPhoneApplicant(?string $phoneApplicant): self
    {
        $this->phoneApplicant = $phoneApplicant;

        return $this;
    }

    public function getFirstNameApplicant(): ?string
    {
        return $this->firstNameApplicant;
    }

    public function setFirstNameApplicant(?string $firstNameApplicant): self
    {
        $this->firstNameApplicant = $firstNameApplicant;

        return $this;
    }

    public function getLastNameApplicant(): ?string
    {
        return $this->lastNameApplicant;
    }

    public function setLastNameApplicant(?string $lastNameApplicant): self
    {
        $this->lastNameApplicant = $lastNameApplicant;

        return $this;
    }

    /**
     * @return Collection|RequestedArtWorks[]
     */
    public function getRequestedArtWorks(): Collection
    {
        return $this->requestedArtWorks;
    }

    public function addRequestedArtWork(RequestedArtWorks $requestedArtWork): self
    {
        if (!$this->requestedArtWorks->contains($requestedArtWork)) {
            $this->requestedArtWorks[] = $requestedArtWork;
            $requestedArtWork->setRequest($this);
        }

        return $this;
    }

    public function removeRequestedArtWork(RequestedArtWorks $requestedArtWork): self
    {
        if ($this->requestedArtWorks->removeElement($requestedArtWork)) {
            // set the owning side to null (unless already changed)
            if ($requestedArtWork->getRequest() === $this) {
                $requestedArtWork->setRequest(null);
            }
        }

        return $this;
    }

}
