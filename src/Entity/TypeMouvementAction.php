<?php

namespace App\Entity;

use App\Repository\TypeMouvementActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeMouvementActionRepository::class)
 */
class TypeMouvementAction
{
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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=TypeMouvement::class, inversedBy="typesMouvementAction")
     */
    private $typeMouvement;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="typeMouvementAction")
     */
    private $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTypeMouvement(): ?TypeMouvement
    {
        return $this->typeMouvement;
    }

    public function setTypeMouvement(?TypeMouvement $typeMouvement): self
    {
        $this->typeMouvement = $typeMouvement;

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
            $action->setTypeMouvementAction($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getTypeMouvementAction() === $this) {
                $action->setTypeMouvementAction(null);
            }
        }

        return $this;
    }
}
