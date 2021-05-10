<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\DepositorRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DepositorRepository::class)
 * @ORM\Table(name="deposant")
 */
class Depositor
{
    use TimestampableEntity;

    /**
     * @JMS\Groups("id")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="sigle", type="string", length=255, nullable=true)
     */
    private $acronym;

    /**
     * @JMS\Groups("depositor", "depositors")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="distrib", type="string", length=255, nullable=true)
     */
    private $distrib;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @JMS\Groups("depositor")
     *
     * @ORM\ManyToOne(targetEntity=DepositType::class, inversedBy="depositors")
     * @ORM\JoinColumn(name="type_deposant_id", referencedColumnName="id")
     */
    private $depositType;

    /**
     * @JMS\Exclude
     *
     * @ORM\OneToMany(targetEntity=DepositStatus::class, mappedBy="depositor")
     */
    private $depositStatuses;

    /**
     * @JMS\Groups("depositor")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
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

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

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
