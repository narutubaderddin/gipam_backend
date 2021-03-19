<?php

namespace App\Entity;

use App\Repository\DepositStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepositStatusRepository::class)
 */
class DepositStatus extends Status
{
    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $inventoryNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Depositor::class, inversedBy="depositStatuses")
     */
    private $depositor;

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
}
