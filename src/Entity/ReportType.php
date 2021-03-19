<?php

namespace App\Entity;

use App\Repository\ReportTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReportTypeRepository::class)
 */
class ReportType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=ReportSubType::class, mappedBy="reportType")
     */
    private $reportSubTypes;

    public function __construct()
    {
        $this->reportSubTypes = new ArrayCollection();
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
