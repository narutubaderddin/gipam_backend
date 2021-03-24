<?php

namespace App\Entity;

use App\Repository\TypeAuteurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeAuteurRepository::class)
 */
class TypeAuteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Auteur::class, inversedBy="types")
     */
    private $auteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getAuteur(): ?auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?auteur$auteur): self
    {
        $this->auteur= $auteur;

        return $this;
    }
}
