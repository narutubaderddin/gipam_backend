<?php

namespace App\Entity;

use App\Repository\PropertyStatusRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropertyStatusRepository::class)
 * @ORM\Table(name="statut_propriete")
 */
class PropertyStatus extends Status
{
    /**
     * @ORM\Column(name="date_entree", type="datetime", nullable=true)
     */
    private $entryDate;

    /**
     * @ORM\Column(name="marquage", type="string", length=255, nullable=true)
     */
    private $marking;

    /**
     * @ORM\ManyToOne(targetEntity=EntryMode::class, inversedBy="propertyStatuses")
     * @ORM\JoinColumn(name="mode_entree_id", referencedColumnName="id")
     */
    private $entryMode;

    /**
     * @ORM\ManyToOne(targetEntity=PropertyStatusCategory::class, inversedBy="propertyStatuses")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(name="propUnPourCent", type="boolean", nullable=true)
     */
    private $propOnePercent;

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
}
