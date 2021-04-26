<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\MovementActionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MovementActionTypeRepository::class)
 * @ORM\Table(name="type_mouvement_action")
 */
class MovementActionType
{
    use TimestampableEntity;
    public const RESERVE = [
        'oeuvreDisponible' => 'Oeuvre disponible',
        'attenteRestitution' => 'En attente de restitution',
        'attenteRestauration' => 'En attente de restauration',
        'stockage' => 'En stockage',
    ];
    public const SORTIE_TEMPORAIRE = [
        'pret' => 'Prêt',
        'depotHorsMEF' => 'Dépôt hors périmètre MEF',
        'restauration' => 'En restauration',
    ];
    public const SORTIE_DEFENITIVE = [
        'retourDeposant' => 'Retour déposant',
        'cession' => 'Cession',
        'transfertDepot' => 'Transfert de dépôt',
        'transfertPropriete' => 'Transfert de propriété',
    ];
    public const LIBELLE = [
        'reserve' => self::RESERVE,
        'temporaire' => self::SORTIE_TEMPORAIRE,
        'definitive' => self::SORTIE_DEFENITIVE,
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
     * @JMS\Groups("movement_action_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $label;

    /**
     * @JMS\Groups("movement_action_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity=MovementType::class, inversedBy="movementActionTypes")
     * @ORM\JoinColumn(name="type_mouvement_id", referencedColumnName="id")
     */
    private $movementType;

    /**
     * @JMS\Exclude()
     *
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="movementActionType")
     */
    private $actions;

    /**
     * @JMS\Groups("movement_action_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
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

    public function getMovementType(): ?MovementType
    {
        return $this->movementType;
    }

    public function setMovementType(?MovementType $movementType): self
    {
        $this->movementType = $movementType;

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
            $action->setMovementActionType($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getMovementActionType() === $this) {
                $action->setMovementActionType(null);
            }
        }

        return $this;
    }
}
