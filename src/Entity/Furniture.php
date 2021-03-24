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
abstract class Furniture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $length;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $depth;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $diameter;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $weight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numberOfUnit;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity=Auteur::class, inversedBy="furniture")
     */
    protected $auteurs;

    /**
     * @ORM\ManyToOne(targetEntity=Epoque::class, inversedBy="furniture")
     */
    protected $epoque;

    /**
     * @ORM\ManyToOne(targetEntity=Style::class, inversedBy="furniture")
     */
    protected $style;

    /**
     * @ORM\ManyToOne(targetEntity=MatiereTechnique::class, inversedBy="furniture")
     */
    protected $matiereTechnique;

    /**
     * @ORM\ManyToOne(targetEntity=Denomination::class, inversedBy="furniture")
     */
    protected $denomination;

    /**
     * @ORM\ManyToOne(targetEntity=domaine::class, inversedBy="furniture")
     */
    protected $domaine;

    /**
     * @ORM\OneToMany(targetEntity=ArtWorkLog::class, mappedBy="furniture")
     */
    protected $artWorkLogs;

    /**
     * @ORM\OneToMany(targetEntity=Movement::class, mappedBy="furniture")
     */
    protected $movements;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="furniture")
     */
    protected $reports;

    /**
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="furniture")
     */
    protected $attachments;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="furniture")
     */
    protected $status;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
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
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        $this->auteurs->removeElement($auteur);

        return $this;
    }

    public function getEpoque(): ?Epoque
    {
        return $this->epoque;
    }

    public function setEpoque(?Epoque $epoque): self
    {
        $this->epoque = $epoque;

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

    public function getMatiereTechnique(): ?MatiereTechnique
    {
        return $this->matiereTechnique;
    }

    public function setMatiereTechnique(?MatiereTechnique $matiereTechnique): self
    {
        $this->matiereTechnique = $matiereTechnique;

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

    public function getDomaine(): ?domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?domaine $domaine): self
    {
        $this->domaine = $domaine;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }
}
