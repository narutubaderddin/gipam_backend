<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\StatusRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 * @ORM\Table(name="statut")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="table_associee", type="string")
 * @ORM\DiscriminatorMap({"statut_propriete"="PropertyStatus", "statut_depot"="DepositStatus"})
 */
abstract class Status
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork", "short")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Groups("id")
     */
    protected $id;

    /**
     * @JMS\Groups("artwork", "short")
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @JMS\Groups("artwork", "short")
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    protected $startDate;

    /**
     * @JMS\Groups("artwork", "short")
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity=Furniture::class, mappedBy="status")
     */
    protected $furniture;

    public function __construct()
    {
        $this->furniture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

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
     * @return Collection|Furniture[]
     */
    public function getFurniture(): Collection
    {
        return $this->furniture;
    }

    public function addFurniture(Furniture $furniture): self
    {
        if (!$this->furniture->contains($furniture)) {
            $this->furniture[] = $furniture;
            $furniture->setStatus($this);
        }

        return $this;
    }

    public function removeFurniture(Furniture $furniture): self
    {
        if ($this->furniture->removeElement($furniture)) {
            // set the owning side to null (unless already changed)
            if ($furniture->getStatus() === $this) {
                $furniture->setStatus(null);
            }
        }

        return $this;
    }
    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("statusType")
     * @JMS\Groups("status_furniture", "short")
     */
    public function getStatusType(){
        return $this instanceof DepositStatus ?'DepositStatus':'PropertyStatus';
    }

    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("depsitorName")
     * @JMS\Groups("status_furniture")
     */
    public function getDepositorName(){
        if($this instanceof  DepositStatus){
            return  $this->getDepositor()->getName();
        }
        return null;
    }
}
