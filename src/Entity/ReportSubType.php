<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ReportSubTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReportSubTypeRepository::class)
 * @ORM\Table(name="sous_type_constat")
 * @UniqueEntity("label", repositoryMethod="iFindBy", message="Un type constat avec ce libellé existe déjà!")
 */
class ReportSubType
{
    use TimestampableEntity;
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

    public const LABEL = [
        'vue' => self::TYPE_VUE,
        'nonVue' => self::TYPE_NON_VUE,
        'identite' => self::TYPE_IDENTITE,
    ];
    /**
     * @JMS\Groups("id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("report_sub_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     *
     * @JMS\Groups("report_sub_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity=ReportType::class, inversedBy="reportSubTypes")
     * @ORM\JoinColumn(name="type_constat_id", referencedColumnName="id")
     */
    private $reportType;

    /**
     *
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="reportSubType")
     */
    private $reports;

    /**
     * @JMS\Groups("report_sub_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
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
