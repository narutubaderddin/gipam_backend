<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\MovementRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=MovementRepository::class)
 * @ORM\Table(name="mouvement")
 */
class Movement
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity=Correspondent::class, inversedBy="movements")
     * @ORM\JoinTable(name="mouvement_correspondant",
     *      joinColumns={@ORM\JoinColumn(name="mouvement_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="correspondant_id", referencedColumnName="id")}
     *      )
     */
    private $correspondents;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="movements")
     * @ORM\JoinColumn(name="localisation_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=MovementType::class, inversedBy="movements")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="movement")
     */
    private $actions;

    /**
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="movements")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")
     */
    private $furniture;

    public function __construct()
    {
        $this->correspondents = new ArrayCollection();
        $this->actions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|Correspondent[]
     */
    public function getCorrespondents(): Collection
    {
        return $this->correspondents;
    }

    public function addCorrespondent(Correspondent $correspondent): self
    {
        if (!$this->correspondents->contains($correspondent)) {
            $this->correspondents[] = $correspondent;
        }

        return $this;
    }

    public function removeCorrespondent(Correspondent $correspondent): self
    {
        $this->correspondents->removeElement($correspondent);

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getType(): ?MovementType
    {
        return $this->type;
    }

    public function setType(?MovementType $type): self
    {
        $this->type = $type;

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
            $action->setMovement($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getMovement() === $this) {
                $action->setMovement(null);
            }
        }

        return $this;
    }

    public function getFurniture(): ?Furniture
    {
        return $this->furniture;
    }

    public function setFurniture(?Furniture $furniture): self
    {
        $this->furniture = $furniture;

        return $this;
    }
}
