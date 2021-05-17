<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\DenominationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=DenominationRepository::class)
 * @ORM\Table(name="denomination")
 */
class Denomination
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("id", "denomination", "denomination_id", "artwork","field_list", "short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("denomination","denomination_furniture","art_work_details","field_list", "short")
     * @ORM\Column(name="libelle", type="string", length=255, nullable=true)
     */
    private $label;

    /**
     * @JMS\Groups("denomination", "id")
     *
     * @ORM\ManyToOne(targetEntity=Field::class, inversedBy="denominations")
     * @ORM\JoinColumn(name="domaine_id", referencedColumnName="id")
     */
    private $field;

    /**
     * @JMS\Exclude()
     *
     * @ORM\ManyToMany(targetEntity=MaterialTechnique::class, inversedBy="denominations")
     * @ORM\JoinTable(name="denomination_matiere_technique",
     *      joinColumns={@ORM\JoinColumn(name="denomination_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="matiere_technique_id", referencedColumnName="id")}
     *      )
     */
    private $materialsTechniques;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="denomination")
     */
    private $furniture;

    /**
     * @JMS\Groups("denomination")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->materialsTechniques = new ArrayCollection();
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
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
     * @return Collection|MaterialTechnique[]
     */
    public function getMaterialsTechniques(): Collection
    {
        return $this->materialsTechniques;
    }

    public function addMaterialsTechnique(MaterialTechnique $materialsTechnique): self
    {
        if (!$this->materialsTechniques->contains($materialsTechnique)) {
            $this->materialsTechniques[] = $materialsTechnique;
            $materialsTechnique->addDenomination($this);
        }

        return $this;
    }

    public function removeMaterialsTechnique(MaterialTechnique $materialsTechnique): self
    {
        if ($this->materialsTechniques->contains($materialsTechnique)) {
            $this->materialsTechniques->removeElement($materialsTechnique);
            $materialsTechnique->removeDenomination($this);
        }

        return $this;
    }

    /**
     * @return Collection|Furniture[]
     */
    public function getFurniture(): Collection
    {
        return $this->furniture;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furniture->contains($furniture)) {
            $this->furniture[] = $furniture;
            $furniture->setDenomination($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getDenomination() === $this) {
                $furniture->setDenomination(null);
            }
        }

        return $this;
    }

    public function getMaterialTechniqueByLabel(string $label): ?MaterialTechnique
    {
        return $this->getMaterialsTechniques()
            ->filter(function (MaterialTechnique $value) use ($label) {
                return $value->getLabel() === $label;
            })->first();
    }
}
