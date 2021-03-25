<?php

namespace App\Entity;

use App\Repository\ObjetMobilierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ObjetMobilierRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"oeuvreArt"="OeuvreArt", "mobilierBureau"="MobilierBureau"})
 */
abstract class ObjetMobilier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $titre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $longueur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $largeur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $hauteur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $profondeur;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $diametre;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $poids;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $nombreUnite;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descriptionCommentaire;

    /**
     * @ORM\ManyToMany(targetEntity=Auteur::class, inversedBy="objetMobilier")
     */
    protected $auteurs;

    /**
     * @ORM\ManyToOne(targetEntity=Epoque::class, inversedBy="objetMobiliers")
     */
    protected $epoque;

    /**
     * @ORM\ManyToOne(targetEntity=Style::class, inversedBy="objetMobiliers")
     */
    protected $style;

    /**
     * @ORM\ManyToOne(targetEntity=MatiereTechnique::class, inversedBy="objetMobiliers")
     */
    protected $matiereTechnique;

    /**
     * @ORM\ManyToOne(targetEntity=Denomination::class, inversedBy="objetMobiliers")
     */
    protected $denomination;

    /**
     * @ORM\ManyToOne(targetEntity=Domaine::class, inversedBy="objetMobiliers")
     */
    protected $domaine;

    /**
     * @ORM\OneToMany(targetEntity=LogOeuvre::class, mappedBy="objetMobilier")
     */
    protected $logOeuvres;

    /**
     * @ORM\OneToMany(targetEntity=Mouvement::class, mappedBy="objetMobilier")
     */
    protected $mouvements;

    /**
     * @ORM\OneToMany(targetEntity=Constat::class, mappedBy="objetMobilier")
     */
    protected $constats;

    /**
     * @ORM\OneToMany(targetEntity=FichierJoint::class, mappedBy="objetMobilier")
     */
    protected $fichierJoints;

    /**
     * @ORM\ManyToOne(targetEntity=Statut::class, inversedBy="objetMobiliers")
     */
    protected $statut;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
        $this->logOeuvres = new ArrayCollection();
        $this->mouvements = new ArrayCollection();
        $this->constats = new ArrayCollection();
        $this->fichierJoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(?float $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(?float $largeur): self
    {
        $this->largeur = $largeur;

        return $this;
    }

    public function getHauteur(): ?float
    {
        return $this->hauteur;
    }

    public function setHauteur(?float $hauteur): self
    {
        $this->hauteur = $hauteur;

        return $this;
    }

    public function getProfondeur(): ?float
    {
        return $this->profondeur;
    }

    public function setProfondeur(?float $profondeur): self
    {
        $this->profondeur = $profondeur;

        return $this;
    }

    public function getDiametre(): ?float
    {
        return $this->diametre;
    }

    public function setDiametre(?float $diametre): self
    {
        $this->diametre = $diametre;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getNombreUnite(): ?int
    {
        return $this->nombreUnite;
    }

    public function setNombreUnite(?int $nombreUnite): self
    {
        $this->nombreUnite = $nombreUnite;

        return $this;
    }

    public function getDescriptionCommentaire(): ?string
    {
        return $this->descriptionCommentaire;
    }

    public function setDescriptionCommentaire(?string $descriptionCommentaire): self
    {
        $this->descriptionCommentaire = $descriptionCommentaire;

        return $this;
    }

    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
        }

        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        $this->auteurs->removeElement($auteur);

        return $this;
    }

    public function getEpoque(): ?Epoque
    {
        return $this->epoque;
    }

    public function setEpoque(?Epoque $epoque): self
    {
        $this->epoque = $epoque;

        return $this;
    }

    public function getStyle(): ?Style
    {
        return $this->style;
    }

    public function setStyle(?Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getMatiereTechnique(): ?MatiereTechnique
    {
        return $this->matiereTechnique;
    }

    public function setMatiereTechnique(?MatiereTechnique $matiereTechnique): self
    {
        $this->matiereTechnique = $matiereTechnique;

        return $this;
    }

    public function getDenomination(): ?Denomination
    {
        return $this->denomination;
    }

    public function setDenomination(?Denomination $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getDomaine(): ?domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?domaine $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * @return Collection|LogOeuvre[]
     */
    public function getLogOeuvres(): Collection
    {
        return $this->logOeuvres;
    }

    public function addLogOeuvre(LogOeuvre $logOeuvre): self
    {
        if (!$this->logOeuvres->contains($logOeuvre)) {
            $this->logOeuvres[] = $logOeuvre;
            $logOeuvre->setObjetMobilier($this);
        }

        return $this;
    }

    public function removeLogOeuvre(LogOeuvre $logOeuvre): self
    {
        if ($this->logOeuvres->removeElement($logOeuvre)) {
            // set the owning side to null (unless already changed)
            if ($logOeuvre->getObjetMobilier() === $this) {
                $logOeuvre->setObjetMobilier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mouvement[]
     */
    public function getMouvements(): Collection
    {
        return $this->mouvements;
    }

    public function addMouvement(Mouvement $mouvement): self
    {
        if (!$this->mouvements->contains($mouvement)) {
            $this->mouvements[] = $mouvement;
            $mouvement->setObjetMobilier($this);
        }

        return $this;
    }

    public function removeMouvement(Mouvement $mouvement): self
    {
        if ($this->mouvements->removeElement($mouvement)) {
            // set the owning side to null (unless already changed)
            if ($mouvement->getObjetMobilier() === $this) {
                $mouvement->setObjetMobilier(null);
            }
        }

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
            $constat->setObjetMobilier($this);
        }

        return $this;
    }

    public function removeConstat(Constat $constat): self
    {
        if ($this->constats->removeElement($constat)) {
            // set the owning side to null (unless already changed)
            if ($constat->getObjetMobilier() === $this) {
                $constat->setObjetMobilier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FichierJoint[]
     */
    public function getFichierJoints(): Collection
    {
        return $this->fichierJoints;
    }

    public function addFichierJoints(FichierJoint $fichierJoint): self
    {
        if (!$this->fichierJoints->contains($fichierJoint)) {
            $this->fichierJoints[] = $fichierJoint;
            $fichierJoint->setObjetMobilier($this);
        }

        return $this;
    }

    public function removeFichierJoints(FichierJoint $fichierJoint): self
    {
        if ($this->fichierJoints->removeElement($fichierJoint)) {
            // set the owning side to null (unless already changed)
            if ($fichierJoint->getObjetMobilier() === $this) {
                $fichierJoint->setObjetMobilier(null);
            }
        }

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
