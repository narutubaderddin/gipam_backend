<?php

namespace App\Entity;

use App\Repository\ArtWorkLogRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtWorkLogRepository::class)
 */
class ArtWorkLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="artWorkLogs")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="artWorkLog")
     */
    private $furniture;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalLength;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalWidth;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalHeight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registrationSignature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descriptiveWords;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $insuranceValue;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $insuranceValueDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $depositDate;

    // todo: meaning and type
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stopNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFurniture(): ?Furniture
    {
        return $this->furniture;
    }

    public function setFurniture(?Furniture $furniture): self
    {
        $this->furniture = $furniture;

        return $this;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getTotalLength(): ?float
    {
        return $this->totalLength;
    }

    public function setTotalLength(?float $totalLength): self
    {
        $this->totalLength = $totalLength;

        return $this;
    }

    public function getTotalWidth(): ?float
    {
        return $this->totalWidth;
    }

    public function setTotalWidth(?float $totalWidth): self
    {
        $this->totalWidth = $totalWidth;

        return $this;
    }

    public function getTotalHeight(): ?float
    {
        return $this->totalHeight;
    }

    public function setTotalHeight(?float $totalHeight): self
    {
        $this->totalHeight = $totalHeight;

        return $this;
    }

    public function getRegistrationSignature(): ?string
    {
        return $this->registrationSignature;
    }

    public function setRegistrationSignature(?string $registrationSignature): self
    {
        $this->registrationSignature = $registrationSignature;

        return $this;
    }

    public function getDescriptiveWords(): ?string
    {
        return $this->descriptiveWords;
    }

    public function setDescriptiveWords(?string $descriptiveWords): self
    {
        $this->descriptiveWords = $descriptiveWords;

        return $this;
    }

    public function getInsuranceValue(): ?int
    {
        return $this->insuranceValue;
    }

    public function setInsuranceValue(?int $insuranceValue): self
    {
        $this->insuranceValue = $insuranceValue;

        return $this;
    }

    public function getInsuranceValueDate(): ?DateTimeInterface
    {
        return $this->insuranceValueDate;
    }

    public function setInsuranceValueDate(DateTimeInterface $insuranceValueDate): self
    {
        $this->insuranceValueDate = $insuranceValueDate;

        return $this;
    }

    public function getDepositDate(): ?\DateTimeInterface
    {
        return $this->depositDate;
    }

    public function setDepositDate(?\DateTimeInterface $depositDate): self
    {
        $this->depositDate = $depositDate;

        return $this;
    }

    public function getStopNumber(): ?string
    {
        return $this->stopNumber;
    }

    public function setStopNumber(?string $stopNumber): self
    {
        $this->stopNumber = $stopNumber;

        return $this;
    }
}
