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
