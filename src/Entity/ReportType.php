<?php

namespace App\Entity;

use App\Repository\ReportTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReportTypeRepository::class)
 * @ORM\Table(name="type_constat")
 */
class ReportType
{
    public const LIBELLE = [
        'vue' => 'vue',
        'nonVue' => 'non vue',
        'identite' => 'identite',
    ];
    /**
     *
     * @JMS\Groups("id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="label should not be blank")
     *
     * @JMS\Groups("report_type")
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $label;

    /**
     *
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=ReportSubType::class, mappedBy="reportType")
     */
    private $reportSubTypes;

    /**
     * @JMS\Groups("report_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->reportSubTypes = new ArrayCollection();
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

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|ReportSubType[]
     */
    public function getReportSubTypes(): Collection
    {
        return $this->reportSubTypes;
    }

    public function addReportSubType(ReportSubType $reportSubType): self
    {
        if (!$this->reportSubTypes->contains($reportSubType)) {
            $this->reportSubTypes[] = $reportSubType;
            $reportSubType->setReportType($this);
        }

        return $this;
    }

    public function removeReportSubType(ReportSubType $reportSubType): self
    {
        if ($this->reportSubTypes->removeElement($reportSubType)) {
            // set the owning side to null (unless already changed)
            if ($reportSubType->getReportType() === $this) {
                $reportSubType->setReportType(null);
            }
        }

        return $this;
    }
}
