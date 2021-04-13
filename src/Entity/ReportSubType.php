<?php

namespace App\Entity;

use App\Repository\ReportSubTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportSubTypeRepository::class)
 * @ORM\Table(name="sous_type_constat")
 */
class ReportSubType
{
    public const TYPE_VUE = [
        'oeuvreRestauree' => 'Oeuvre restaurée',
        'bonEtat' => 'Bon état',
        'stableAvecIntervention' => 'Etat stable avec intervention à programmer',
        'interventionUrgente' => 'Intervention urgente de sauvegarde nécessaire',
        'aChanger' => 'Encadrement ou socle à changer',
        'reglageARealiser' => 'Petits travaux ou réglage à réaliser',
        'detruite' => 'Oeuvre détruite',
        'voirCommentaire' => 'Voir commentaire'
    ];

    public const TYPE_IDENTITE = [
        'recolementSoa' => 'Récolement SOA',
        'recolementDeposant' => 'Récolement par le déposant',
        'controleCorrespondant' => 'Contrôle d’inventaire par le correspondant',
    ];

    public const TYPE_NON_VUE = ['nonVue' => 'Non vue'];

    public const LIBELLE = [
        'vue' => self::TYPE_VUE,
        'nonVue' => self::TYPE_NON_VUE,
        'identite' => self::TYPE_IDENTITE,
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=ReportType::class, inversedBy="reportSubTypes")
     * @ORM\JoinColumn(name="type_constat_id", referencedColumnName="id")
     */
    private $reportType;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="reportSubType")
     */
    private $reports;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getReportType(): ?ReportType
    {
        return $this->reportType;
    }

    public function setReportType(?ReportType $reportType): self
    {
        $this->reportType = $reportType;

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
            $report->setReportSubType($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getReportSubType() === $this) {
                $report->setReportSubType(null);
            }
        }

        return $this;
    }
}
