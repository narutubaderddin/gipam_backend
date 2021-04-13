<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlertRepository::class)
 * @ORM\Table(name="alerte")
 */
class Alert
{
    /**
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
     * @ORM\ManyToOne(targetEntity=Action::class, inversedBy="alerts")
     * @ORM\JoinColumn(name="action_id", referencedColumnName="id")
     */
    private $action;

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

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }
}
