<?php

namespace App\Entity;

use App\Repository\FurnitureRepository;
use App\Services\ArtWorkService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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
     * @JMS\Groups("artwork", "artwork_id","id","art_work_list","art_work_details","request_list","request_details","request_list")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @JMS\Groups({"art_work_list","artwork","art_work_details","request_list","request_details","request_list"})
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @JMS\Groups("artwork","art_work_details","art_work_list")
     * @ORM\Column(name="longueur", type="float", nullable=true)
     */
    protected $length;

    /**
     * @JMS\Groups("artwork","art_work_details","art_work_list")
     * @ORM\Column(name="largeur", type="float", nullable=true)
     */
    protected $width;

    /**
     * @JMS\Groups("artwork","art_work_details")
     * @ORM\Column(name="hauteur", type="float", nullable=true)
     */
    protected $height;

    /**
     * @JMS\Groups("artwork","art_work_details")
     * @ORM\Column(name="profondeur", type="float", nullable=true)
     */
    protected $depth;

    /**
     * @JMS\Groups("artwork","art_work_details")
     * @ORM\Column(name="diametre", type="float", nullable=true)
     */
    protected $diameter;

    /**
     * @JMS\Groups("artwork","art_work_details")
     * @ORM\Column(name="poids", type="float", nullable=true)
     */
    protected $weight;

    /**
     * @JMS\Groups("artwork","art_work_details")
     * @ORM\Column(name="nombre_unite", type="integer", nullable=true)
     */
    protected $numberOfUnit;

    /**
     * @JMS\Groups("artwork", "artwork_author","furniture","furniture_author","art_work_list","art_work_details","request_list")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="furniture")
     * @ORM\JoinTable(name="objet_mobilier_auteur",
     *      joinColumns={@ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="auteur_id", referencedColumnName="id")}
     *      )
     *
     */
    protected $authors;

    /**
     * @JMS\Groups("artwork","era_furniture","art_work_details")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Era::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="epoque_id", referencedColumnName="id")
     */
    protected $era;

    /**
     * @JMS\Groups("artwork","style_furniture","art_work_details")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Style::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     */
    protected $style;

    /**
     * @JMS\Groups("artwork","materialTechnique_furniture","art_work_details")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=MaterialTechnique::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="matiere_technique_id", referencedColumnName="id")
     */
    protected $materialTechnique;

    /**
     * @JMS\Groups("artwork","denomination_furniture","art_work_details")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Denomination::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="denomination_id", referencedColumnName="id")
     */
    protected $denomination;

    /**
     * @JMS\Groups("artwork","field_furniture","art_work_details","art_work_list")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="domaine_id", referencedColumnName="id")
     */
    protected $field;

    /**
     * @JMS\Groups("artwork")
     * @ORM\OneToMany(targetEntity=ArtWorkLog::class, mappedBy="furniture")
     */
    protected $artWorkLogs;

    /**
     * @JMS\Groups("artwork","mouvement_furniture","art_work_details")
     * @JMS\MaxDepth(1)
     * @ORM\OneToMany(targetEntity=Movement::class, mappedBy="furniture")
     */
    protected $movements;

    /**
     * @JMS\Groups("artwork","art_work_details")
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="furniture")
     * @JMS\MaxDepth(1)
     */
    protected $reports;

    /**
     * @JMS\Groups("artwork","art_work_list","art_work_details")
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="furniture", cascade={"persist", "remove"})
     */
    protected $attachments;

    /**
     * @JMS\Groups("artwork","status_furniture","art_work_details")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="furniture", cascade={"persist", "remove"})
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="furniture")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @JMS\Groups("artwork", "art_work_details")
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=Hyperlink::class, mappedBy="furniture", cascade={"persist", "remove"})
     */
    protected $hyperlinks;

    /**
     * @JMS\Groups("artwork", "art_work_details")
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=Photography::class, mappedBy="furniture", cascade={"persist", "remove"})
     */
    protected $photographies;

    /**
     * @JMS\Groups("artwork","status_furniture")
     * @ORM\Column(type="boolean")
     * @ORM\Column(type="boolean",options={"default"=true})
     */
    protected $visible = true;

    /**
     * @JMS\Exclude()
     * @JMS\MaxDepth(1)
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="parent")
     */
    protected $children;

    /**
     * @JMS\Groups("artwork")
     * @JMS\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var \DateTime
     * @JMS\Groups("artwork","art_work_list")
     * @Gedmo\Timestampable(on="create")
     * @JMS\Type("DateTime<'Y-m-d'>")
     * @JMS\SerializedName("creationDate")
     * @ORM\Column(name="date_creation", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @JMS\Groups("artwork")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="date_modification", type="datetime")
     */
    protected $updatedAt;


    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->artWorkLogs = new ArrayCollection();
        $this->movements = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->hyperlinks = new ArrayCollection();
        $this->photographies = new ArrayCollection();
        $this->children = new ArrayCollection();
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

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Furniture $furniture
     * @return $this
     */
    public function addChild(Furniture $furniture): self
    {
        if (!$this->children->contains($furniture)) {
            $this->children[] = $furniture;
            $furniture->setParent($this);
        }

        return $this;
    }

    /**
     * @param Furniture $furniture
     * @return $this
     */
    public function removeChild(Furniture $furniture): self
    {
        if ($this->children->contains($furniture)) {
            $this->children->removeElement($furniture);
            $furniture->setParent(null);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Furniture|null $parent
     * @return $this
     */
    public function setParent(?Furniture $parent): self
    {
        $this->parent = $parent;

        return $this;
    }



    public function getLastReport(){

    }

    /**
     * Sets createdAt.
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }


    /**
     * Sets updatedAt.
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getDenomination() instanceof Denomination &&
            $this->getDenomination()->getField() instanceof Field &&
            $this->getField() instanceof Field
        ){
            if ($this->getDenomination()->getField() != $this->getField()){
                $context->buildViolation('This Denomination have mapping with another Field')
                    ->atPath('field')
                    ->addViolation();
            }
        }
    }
    /**
     *
     * @JMS\Groups("art_work_list","art_work_details")
     * @JMS\VirtualProperty(name="principalPhoto")
     * @return string|null
     */

    public function getPrincipalPhoto(){
        foreach ($this->photographies as $photography){
            if($photography->getPhotographyType()->getType()==PhotographyType::TYPE['principle']){
                return $photography;
            }
        }
        return  null;
    }


}
