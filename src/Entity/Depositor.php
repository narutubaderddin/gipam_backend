<?php

namespace App\Entity;

use App\Repository\DepositorRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepositorRepository::class)
 */
class Depositor
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
    private $acronym;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    // todo: meaning and type
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dpt;

    // todo: meaning and type
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $distrib;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    // Todo: attribute meaning and type
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contant;

    /**
     * @ORM\ManyToOne(targetEntity=DepositType::class, inversedBy="depositors")
     */
    private $depositType;

    /**
     * @ORM\OneToMany(targetEntity=DepositStatus::class, mappedBy="depositor")
     */
    private $depositStatuses;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->depositStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): self
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDpt(): ?string
    {
        return $this->dpt;
    }

    public function setDpt(?string $dpt): self
    {
        $this->dpt = $dpt;

        return $this;
    }

    public function getDistrib(): ?string
    {
        return $this->distrib;
    }

    public function setDistrib(?string $distrib): self
    {
        $this->distrib = $distrib;

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

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getMel(): ?string
    {
        return $this->mel;
    }

    public function setMel(?string $mel): self
    {
        $this->mel = $mel;

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

    public function getContant(): ?string
    {
        return $this->contant;
    }

    public function setContant(?string $contant): self
    {
        $this->contant = $contant;

        return $this;
    }

    public function getDepositType(): ?DepositType
    {
        return $this->depositType;
    }

    public function setDepositType(?DepositType $depositType): self
    {
        $this->depositType = $depositType;

        return $this;
    }

    /**
     * @return Collection|DepositStatus[]
     */
    public function getDepositStatuses(): Collection
    {
        return $this->depositStatuses;
    }

    public function addDepositStatus(DepositStatus $depositStatus): self
    {
        if (!$this->depositStatuses->contains($depositStatus)) {
            $this->depositStatuses[] = $depositStatus;
            $depositStatus->setDepositor($this);
        }

        return $this;
    }

    public function removeDepositStatus(DepositStatus $depositStatus): self
    {
        if ($this->depositStatuses->removeElement($depositStatus)) {
            // set the owning side to null (unless already changed)
            if ($depositStatus->getDepositor() === $this) {
                $depositStatus->setDepositor(null);
            }
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
}
