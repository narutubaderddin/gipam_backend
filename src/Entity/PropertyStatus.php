<?php

namespace App\Entity;

use App\Repository\PropertyStatusRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyStatusRepository::class)
 */
class PropertyStatus extends Status
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $entryDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marking;

    // todo: meaning in the UML class diagram: prop_1_pour_100 for Statut_Propriété
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $propertyPercentage;

    /**
     * @ORM\ManyToOne(targetEntity=EntryMode::class, inversedBy="propertyStatuses")
     */
    private $entryMode;

    /**
     * @ORM\ManyToOne(targetEntity=PropertyStatusCategory::class, inversedBy="propertyStatuses")
     */
    private $category;

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

    public function getPropertyPercentage(): ?int
    {
        return $this->propertyPercentage;
    }

    public function setPropertyPercentage(?int $propertyPercentage): self
    {
        $this->propertyPercentage = $propertyPercentage;

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
}
