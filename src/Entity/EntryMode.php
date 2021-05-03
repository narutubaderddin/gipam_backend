<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\EntryModeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=EntryModeRepository::class)
 * @ORM\Table(name="mode_entree")
 */
class EntryMode
{
    use TimestampableEntity;
    public const LABEL = [
        'inscriptionInventaire' => 'Inscription à l’inventaire',
        'don' => 'Don',
        'acquisition' => 'Acquisition',
        'transfertPropriété' => 'Transfert de propriété',
        'artistique' => '1% artistique',
    ];
    /**
     * @JMS\Groups("artwork")
     *
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
     * @ORM\OneToMany(targetEntity=PropertyStatus::class, mappedBy="entryMode")
     */
    private $propertyStatuses;

    /**
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;
    public function __construct()
    {
        $this->propertyStatuses = new ArrayCollection();
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

    /**
     * @return Collection|PropertyStatus[]
     */
    public function getPropertyStatuses(): Collection
    {
        return $this->propertyStatuses;
    }

    public function addPropertyStatus(PropertyStatus $propertyStatus): self
    {
        if (!$this->propertyStatuses->contains($propertyStatus)) {
            $this->propertyStatuses[] = $propertyStatus;
            $propertyStatus->setEntryMode($this);
        }

        return $this;
    }

    public function removePropertyStatus(PropertyStatus $propertyStatus): self
    {
        if ($this->propertyStatuses->removeElement($propertyStatus)) {
            // set the owning side to null (unless already changed)
            if ($propertyStatus->getEntryMode() === $this) {
                $propertyStatus->setEntryMode(null);
            }
        }

        return $this;
    }
}
