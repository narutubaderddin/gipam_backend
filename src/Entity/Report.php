<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 * @ORM\Table(name="constat")
 */
class Report
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime")
     * @JMS\Groups("art_work_details","report_list")
     */
    private $date;

    /**
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     * @JMS\Groups("art_work_details")
     */
    private $comment;

    // todo: meaning and type for titre_percep in UML Class. In SFD "Titre de perception"
    /**
     * @ORM\Column(name="titre_perception", type="boolean", nullable=true)
     */
    private $collectionTitle;

    /**
     * @ORM\ManyToOne(targetEntity=ReportSubType::class, inversedBy="reports")
     * @ORM\JoinColumn(name="sous_type_constat_id", referencedColumnName="id")
     */
    private $reportSubType;

    /**
     * @JMS\Groups("report_list")
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="report")
     */
    private $actions;

    /**
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="reports")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")
     */
    private $furniture;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups("report_list")
     */
    private $status;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getCollectionTitle(): ?bool
    {
        return $this->collectionTitle;
    }

    public function setCollectionTitle(?bool $collectionTitle): self
    {
        $this->collectionTitle = $collectionTitle;

        return $this;
    }

    public function getReportSubType(): ?ReportSubType
    {
        return $this->reportSubType;
    }

    public function setReportSubType(?ReportSubType $reportSubType): self
    {
        $this->reportSubType = $reportSubType;

        return $this;
    }

    /**
     * @return Collection|Action[]
     */
    public function getActions(): Collection
    {
        return $this->actions;
    }

    public function addAction(Action $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->setReport($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getReport() === $this) {
                $action->setReport(null);
            }
        }

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     * @return string|null
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("conclusion")
     * @JMS\Groups("report_list")
     */
    public function getConclusion (){
        return $this->getStatus() === 'VU' ? $this->getComment() : null;
    }
}
