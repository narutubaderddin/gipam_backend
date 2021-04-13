<?php

namespace App\Entity;

use App\Repository\DepositTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepositTypeRepository::class)
 * @ORM\Table(name="type_deposant")
 */
class DepositType
{
    public const LIBELLE = [
        'particuliers' => 'Particuliers',
        'institutionsPrivees' => 'Institutions privées',
        'serviceMuseesFrance' => 'Service des Musées de France',
        'autresMusees' => 'Autres Musées',
        'autresAdministrations' => 'Autres Administrations',
        'etablissementMinistereCulture' => 'Etablissements du Ministère de la Culture',
    ];
    /**
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
     * @ORM\OneToMany(targetEntity=Depositor::class, mappedBy="depositType")
     */
    private $depositors;

    public function __construct()
    {
        $this->depositors = new ArrayCollection();
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
     * @return Collection|Depositor[]
     */
    public function getDepositors(): Collection
    {
        return $this->depositors;
    }

    public function addDepositor(Depositor $depositor): self
    {
        if (!$this->depositors->contains($depositor)) {
            $this->depositors[] = $depositor;
            $depositor->setDepositType($this);
        }

        return $this;
    }

    public function removeDepositor(Depositor $depositor): self
    {
        if ($this->depositors->removeElement($depositor)) {
            // set the owning side to null (unless already changed)
            if ($depositor->getDepositType() === $this) {
                $depositor->setDepositType(null);
            }
        }

        return $this;
    }
}
