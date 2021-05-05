<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\ActionReportTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ActionReportTypeRepository::class)
 * @ORM\Table(name="type_constat_action")
 * @UniqueEntity("label", repositoryMethod="iFindBy", message="Un type action constat avec ce libellé existe déjà!")
 */
class ActionReportType
{
    use TimestampableEntity;

    public const LABEL = [
      'enRecherche' => 'En recherche',
      'depotPlainte' => 'Dépôt de plainte',
      'abandonRecherche' => 'Abandon des recherches',
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
     * @JMS\Groups("action_type")
     *
     * @Assert\NotBlank
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $label;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="type")
     */
    private $actions;

    /**
     * @JMS\Groups("action_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
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
            $action->setType($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getType() === $this) {
                $action->setType(null);
            }
        }

        return $this;
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
}
