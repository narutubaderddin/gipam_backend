<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\RequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="numeroPiece",type="string", length=50, nullable=true)
     */
    private $pieceNumber;

    /**
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=SubDivision::class, inversedBy="requests")
     * @ORM\JoinColumn(name="sous_direction_id", referencedColumnName="id")
     */
    private $subDivision;

    /**
     * @ORM\ManyToOne(targetEntity=Establishment::class, inversedBy="requests")
     * @ORM\JoinColumn(name="etablissement_id", referencedColumnName="id")
     */
    private $establishement;

    /**
     * @ORM\Column(name="fonction",type="string", length=255, nullable=true)
     */
    private $function;

    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="requests")
     * @ORM\JoinColumn(name="batiment_id", referencedColumnName="id")
     */
    private $building;

    /**
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(name="telephone",type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=ArtWork::class, mappedBy="request")
     */
    private $artWorks;

    public function __construct()
    {
        $this->artWorks = new ArrayCollection();
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

    public function getSubDivision(): ?SubDivision
    {
        return $this->subDivision;
    }

    public function setSubDivision(?SubDivision $subDivision): self
    {
        $this->subDivision = $subDivision;

        return $this;
    }

    public function getEstablishement(): ?Establishment
    {
        return $this->establishement;
    }

    public function setEstablishement(?Establishment $establishement): self
    {
        $this->establishement = $establishement;

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

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): self
    {
        $this->building = $building;

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

    /**
     * @return Collection|ArtWork[]
     */
    public function getArtWorks(): Collection
    {
        return $this->artWorks;
    }

    public function addArtWork(ArtWork $artWork): self
    {
        if (!$this->artWorks->contains($artWork)) {
            $this->artWorks[] = $artWork;
            $artWork->setRequest($this);
        }

        return $this;
    }

    public function removeArtWork(ArtWork $artWork): self
    {
        if ($this->artWorks->removeElement($artWork)) {
            // set the owning side to null (unless already changed)
            if ($artWork->getRequest() === $this) {
                $artWork->setRequest(null);
            }
        }

        return $this;
    }

}
