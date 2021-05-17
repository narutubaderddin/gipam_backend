<?php

namespace App\Entity;

use App\Repository\PropertyStatusRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PropertyStatusRepository::class)
 * @ORM\Table(name="statut_propriete")
 */
class PropertyStatus extends Status
{
    /**
     * @JMS\Groups("artwork", "short")
     *
     * @ORM\Column(name="date_entree", type="datetime", nullable=true)
     */
    private $entryDate;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="marquage", type="string", length=255, nullable=true)
     */
    private $marking;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\ManyToOne(targetEntity=EntryMode::class, inversedBy="propertyStatuses")
     * @ORM\JoinColumn(name="mode_entree_id", referencedColumnName="id")
     */
    private $entryMode;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\ManyToOne(targetEntity=PropertyStatusCategory::class, inversedBy="propertyStatuses")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="propUnPourCent", type="boolean", nullable=true)
     */
    private $propOnePercent;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="signature_inscription", type="string", length=255, nullable=true)
     */
    private $registrationSignature;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="mots_descriptifs", type="string", length=255, nullable=true)
     */
    private $descriptiveWords;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="valeur_assurance", type="integer", nullable=true)
     */
    private $insuranceValue;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="date_valeur_assurance", type="datetime", nullable=true)
     */
    private $insuranceValueDate;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="autres_inscription", type="string", length=255, nullable=true)
     */
    private $otherRegistrations;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="description_commentaire", type="text", nullable=true)
     */
    protected $description;

    public function getEntryDate(): ?DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(?DateTimeInterface $entryDate): self
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    public function getMarking(): ?string
    {
        return $this->marking;
    }

    public function setMarking(?string $marking): self
    {
        $this->marking = $marking;

        return $this;
    }

    public function getEntryMode(): ?EntryMode
    {
        return $this->entryMode;
    }

    public function setEntryMode(?EntryMode $entryMode): self
    {
        $this->entryMode = $entryMode;

        return $this;
    }

    public function getCategory(): ?PropertyStatusCategory
    {
        return $this->category;
    }

    public function setCategory(?PropertyStatusCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPropOnePercent(): ?bool
    {
        return $this->propOnePercent;
    }

    public function setPropOnePercent(?bool $propertyPercentage): self
    {
        $this->propOnePercent = $propertyPercentage;

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

    public function setInsuranceValueDate(?DateTimeInterface $insuranceValueDate): self
    {
        $this->insuranceValueDate = $insuranceValueDate;

        return $this;
    }

    public function getOtherRegistrations(): ?string
    {
        return $this->otherRegistrations;
    }

    public function setOtherRegistrations(?string $otherRegistrations): self
    {
        $this->otherRegistrations = $otherRegistrations;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     *
     * @JMS\Groups("artwork")
     * @JMS\VirtualProperty(name="inventoryNumber")
     * @return int|null
     */
    public function getInventoryNumber()
    {
        return $this->getId();
    }
}
