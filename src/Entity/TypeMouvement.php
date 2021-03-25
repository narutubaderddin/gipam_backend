<?php

namespace App\Entity;

use App\Repository\TypeMouvementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeMouvementRepository::class)
 */
class TypeMouvement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Mouvement::class, mappedBy="type")
     */
    private $mouvements;

    /**
     * @ORM\OneToMany(targetEntity=TypeMouvementAction::class, mappedBy="typeMouvement")
     */
    private $typesMouvementAction;

    public function __construct()
    {
        $this->mouvements = new ArrayCollection();
        $this->typesMouvementAction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?\DateTimeInterface
    {
        return $this->libelle;
    }

    public function setLibelle(?\DateTimeInterface $libelle): self
    {
        $this->libelle = $libelle;

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
            $mouvement->setType($this);
        }

        return $this;
    }

    public function removeMouvement(Mouvement $mouvement): self
    {
        if ($this->mouvements->removeElement($mouvement)) {
            // set the owning side to null (unless already changed)
            if ($mouvement->getType() === $this) {
                $mouvement->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TypeMouvementAction[]
     */
    public function getTypesMouvementAction(): Collection
    {
        return $this->typesMouvementAction;
    }

    public function addTypeMouvementAction(TypeMouvementAction $typeMouvementAction): self
    {
        if (!$this->typesMouvementAction->contains($typeMouvementAction)) {
            $this->typesMouvementAction[] = $typeMouvementAction;
            $typeMouvementAction->setTypeMouvement($this);
        }

        return $this;
    }

    public function removeTypeMouvementAction(TypeMouvementAction $typeMouvementAction): self
    {
        if ($this->typesMouvementAction->removeElement($typeMouvementAction)) {
            // set the owning side to null (unless already changed)
            if ($typeMouvementAction->getTypeMouvement() === $this) {
                $typeMouvementAction->setTypeMouvement(null);
            }
        }

        return $this;
    }
}
