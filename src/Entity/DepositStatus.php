<?php

namespace App\Entity;

use App\Repository\DepositStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=DepositStatusRepository::class)
 * @ORM\Table(name="statut_depot")
 */
class DepositStatus extends Status
{
    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="numero_inventaire", type="integer", length=255, nullable=true)
     */
    private $inventoryNumber;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\ManyToOne(targetEntity=Depositor::class, inversedBy="depositStatuses")
     * @ORM\JoinColumn(name="deposant_id", referencedColumnName="id")
     */
    private $depositor;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="date_depot", type="datetime", nullable=true)
     */
    private $depositDate;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="numero_arret", type="integer", nullable=true)
     */
    private $stopNumber;

    public function getInventoryNumber(): ?string
    {
        return $this->inventoryNumber;
    }

    public function setInventoryNumber(?string $inventoryNumber): self
    {
        $this->inventoryNumber = $inventoryNumber;

        return $this;
    }

    public function getDepositor(): ?Depositor
    {
        return $this->depositor;
    }

    public function setDepositor(?Depositor $depositor): self
    {
        $this->depositor = $depositor;

        return $this;
    }

    public function getDepositDate(): ?\DateTimeInterface
    {
        return $this->depositDate;
    }

    public function setDepositDate(?\DateTimeInterface $depositDate): self
    {
        $this->depositDate = $depositDate;

        return $this;
    }

    public function getStopNumber(): ?string
    {
        return $this->stopNumber;
    }

    public function setStopNumber(?string $stopNumber): self
    {
        $this->stopNumber = $stopNumber;

        return $this;
    }
}
