<?php

namespace App\Entity;

use App\Repository\LogOeuvreRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogOeuvreRepository::class)
 */
class LogOeuvre
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
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=ObjetMobilier::class, inversedBy="logOeuvres")
     */
    private $objetMobilier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }



    public function getObjetMobilier(): ?ObjetMobilier
    {
        return $this->objetMobilier;
    }

    public function setObjetMobilier(?ObjetMobilier $objetMobilier): self
    {
        $this->objetMobilier = $objetMobilier;

        return $this;
    }
}
