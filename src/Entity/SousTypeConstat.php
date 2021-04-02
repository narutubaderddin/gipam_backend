<?php

namespace App\Entity;

use App\Repository\SousTypeConstatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SousTypeConstatRepository::class)
 */
class SousTypeConstat
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=TypeConstat::class, inversedBy="sousTypesConstat")
     */
    private $typeConstat;

    /**
     * @ORM\OneToMany(targetEntity=Constat::class, mappedBy="sousTypeConstat")
     */
    private $constats;

    public function __construct()
    {
        $this->constats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getTypeConstat(): ?TypeConstat
    {
        return $this->typeConstat;
    }

    public function setTypeConstat(?TypeConstat $typeConstat): self
    {
        $this->typeConstat = $typeConstat;

        return $this;
    }

    /**
     * @return Collection|Constat[]
     */
    public function getConstats(): Collection
    {
        return $this->constats;
    }

    public function addConstat(Constat $constat): self
    {
        if (!$this->constats->contains($constat)) {
            $this->constats[] = $constat;
            $constat->setSousTypeConstat($this);
        }

        return $this;
    }

    public function removeConstat(Constat $constat): self
    {
        if ($this->constats->removeElement($constat)) {
            // set the owning side to null (unless already changed)
            if ($constat->getSousTypeConstat() === $this) {
                $constat->setSousTypeConstat(null);
            }
        }

        return $this;
    }
}
