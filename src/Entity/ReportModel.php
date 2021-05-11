<?php

namespace App\Entity;

use App\Repository\ReportModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReportModelRepository::class)
 * @ORM\Table(name="modele_constat")
 */
class ReportModel
{
    /**
     * @JMS\Groups("id", "short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("report_model", "short")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $name;

    /**
     * @JMS\Groups("report_model")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="texte", type="text")
     */
    private $text;

    /**
     * @JMS\Groups("report_model")
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $active = true;

    /**
     * @JMS\Groups("report_model")
     *
     * @ORM\ManyToMany(targetEntity=Field::class, inversedBy="reportModels")
     * @ORM\JoinTable(
     *     name="modele_constat_domaine",
     *     joinColumns={@ORM\JoinColumn(name="modele_constat_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="domaine_id", referencedColumnName="id")}
     *     )
     */
    private $fields;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Field[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
        }

        return $this;
    }

    public function removeField(Field $field): self
    {
        $this->fields->removeElement($field);

        return $this;
    }
}
