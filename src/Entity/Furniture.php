<?php

namespace App\Entity;

use App\Repository\FurnitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FurnitureRepository::class)
 * @ORM\Table(name="objet_mobilier")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="table_associee", type="string")
 * @ORM\DiscriminatorMap({"oeuvre_art"="ArtWork", "mobilier_bureau"="OfficeFurniture"})
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="longueur", type="string", length=100, nullable=true)
     */
    protected $length;

    /**
     * @ORM\Column(name="largeur", type="string", length=100, nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(name="hauteur", type="string", length=100, nullable=true)
     */
    protected $height;

    /**
     * @ORM\Column(name="profondeur", type="string", length=100, nullable=true)
     */
    protected $depth;

    /**
     * @ORM\Column(name="diametre", type="string", length=100, nullable=true)
     */
    protected $diameter;

    /**
     * @ORM\Column(name="poids", type="string", length=100, nullable=true)
     */
    protected $weight;

    /**
     * @ORM\Column(name="nombre_unite", type="integer", nullable=true)
     */
    protected $numberOfUnit;

    /**
     * @ORM\Column(name="description_commentaire", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="furniture")
     * @ORM\JoinTable(name="objet_mobilier_auteur",
     *      joinColumns={@ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="auteur_id", referencedColumnName="id")}
     *      )
     */
    protected $authors;

    /**
     * @ORM\ManyToOne(targetEntity=Era::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="epoque_id", referencedColumnName="id")
     */
    protected $era;

    /**
     * @ORM\ManyToOne(targetEntity=Style::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     */
    protected $style;

    /**
     * @ORM\ManyToOne(targetEntity=MaterialTechnique::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="matiere_technique_id", referencedColumnName="id")
     */
    protected $materialTechnique;

    /**
     * @ORM\ManyToOne(targetEntity=Denomination::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="denomination_id", referencedColumnName="id")
     */
    protected $denomination;

    /**
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="domaine_id", referencedColumnName="id")
     */
    protected $field;

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
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\OneToMany(targetEntity=Hyperlink::class, mappedBy="furniture")
     */
    private $hyperlinks;

    /**
     * @ORM\OneToMany(targetEntity=Photography::class, mappedBy="furniture")
     */
    private $photographies;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visible;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->artWorkLogs = new ArrayCollection();
        $this->movements = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->hyperlinks = new ArrayCollection();
        $this->photographies = new ArrayCollection();
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

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(?string $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getDepth(): ?string
    {
        return $this->depth;
    }

    public function setDepth(?string $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    public function getDiameter(): ?string
    {
        return $this->diameter;
    }

    public function setDiameter(?string $diameter): self
    {
        $this->diameter = $diameter;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): self
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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Hyperlink[]
     */
    public function getHyperlinks(): Collection
    {
        return $this->hyperlinks;
    }

    public function addHyperlink(Hyperlink $hyperlink): self
    {
        if (!$this->hyperlinks->contains($hyperlink)) {
            $this->hyperlinks[] = $hyperlink;
            $hyperlink->setFurniture($this);
        }

        return $this;
    }

    public function removeHyperlink(Hyperlink $hyperlink): self
    {
        if ($this->hyperlinks->removeElement($hyperlink)) {
            // set the owning side to null (unless already changed)
            if ($hyperlink->getFurniture() === $this) {
                $hyperlink->setFurniture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Photography[]
     */
    public function getPhotographies(): Collection
    {
        return $this->photographies;
    }

    public function addPhotography(Photography $photography): self
    {
        if (!$this->photographies->contains($photography)) {
            $this->photographies[] = $photography;
            $photography->setFurniture($this);
        }

        return $this;
    }

    public function removePhotography(Photography $photography): self
    {
        if ($this->photographies->removeElement($photography)) {
            // set the owning side to null (unless already changed)
            if ($photography->getFurniture() === $this) {
                $photography->setFurniture(null);
            }
        }

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}
