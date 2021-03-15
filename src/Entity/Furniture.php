<?php

namespace App\Entity;

use App\Repository\FurnitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FurnitureRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"artWork"="ArtWork", "officeFurniture"="OfficeFurniture"})
 */
Abstract class Furniture
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
    private $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $length;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $depth;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $diameter;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberOfUnit;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="furnitures")
     */
    private $authors;

    /**
     * @ORM\ManyToOne(targetEntity=Era::class, inversedBy="furnitures")
     */
    private $era;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="furniture")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Style::class, inversedBy="furniture")
     */
    private $style;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialTechnique::class, inversedBy="furniture")
     */
    private $materialTechnique;

    /**
     * @ORM\ManyToOne(targetEntity=Denomination::class, inversedBy="furniture")
     */
    private $denomination;

    /**
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="furniture")
     */
    private $field;

    /**
     * @ORM\OneToMany(targetEntity=ArtWorkLog::class, mappedBy="furniture")
     */
    private $artWorkLogs;

    /**
     * @ORM\OneToMany(targetEntity=Movement::class, mappedBy="furniture")
     */
    private $movements;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="furniture")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="furniture")
     */
    private $attachments;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->artWorkLogs = new ArrayCollection();
        $this->movements = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDepth(): ?float
    {
        return $this->depth;
    }

    public function setDepth(?float $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getDiameter(): ?float
    {
        return $this->diameter;
    }

    public function setDiameter(?float $diameter): self
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getNumberOfUnit(): ?int
    {
        return $this->numberOfUnit;
    }

    public function setNumberOfUnit(?int $numberOfUnit): self
    {
        $this->numberOfUnit = $numberOfUnit;

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
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    public function getEra(): ?Era
    {
        return $this->era;
    }

    public function setEra(?Era $era): self
    {
        $this->era = $era;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStyle(): ?Style
    {
        return $this->style;
    }

    public function setStyle(?Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getMaterialTechnique(): ?MaterialTechnique
    {
        return $this->materialTechnique;
    }

    public function setMaterialTechnique(?MaterialTechnique $materialTechnique): self
    {
        $this->materialTechnique = $materialTechnique;

        return $this;
    }

    public function getDenomination(): ?Denomination
    {
        return $this->denomination;
    }

    public function setDenomination(?Denomination $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): self
    {
        $this->field = $field;

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
            $artWorkLog->setFurniture($this);
        }

        return $this;
    }

    public function removeArtWorkLog(ArtWorkLog $artWorkLog): self
    {
        if ($this->artWorkLogs->removeElement($artWorkLog)) {
            // set the owning side to null (unless already changed)
            if ($artWorkLog->getFurniture() === $this) {
                $artWorkLog->setFurniture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Movement[]
     */
    public function getMovements(): Collection
    {
        return $this->movements;
    }

    public function addMovement(Movement $movement): self
    {
        if (!$this->movements->contains($movement)) {
            $this->movements[] = $movement;
            $movement->setFurniture($this);
        }

        return $this;
    }

    public function removeMovement(Movement $movement): self
    {
        if ($this->movements->removeElement($movement)) {
            // set the owning side to null (unless already changed)
            if ($movement->getFurniture() === $this) {
                $movement->setFurniture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setFurniture($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getFurniture() === $this) {
                $report->setFurniture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setFurniture($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getFurniture() === $this) {
                $attachment->setFurniture(null);
            }
        }

        return $this;
    }
}
