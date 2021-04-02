<?php

namespace App\Entity;

use App\Repository\FichierJointRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FichierJointRepository::class)
 */
class FichierJoint
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien;

    /**
     * @ORM\Column(type="boolean")
     */
    private $imagePrincipale;

    /**
     * @ORM\ManyToOne(targetEntity=ObjetMobilier::class, inversedBy="fichierJoints")
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

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getImagePrincipale(): ?bool
    {
        return $this->imagePrincipale;
    }

    public function setImagePrincipale(bool $imagePrincipale): self
    {
        $this->imagePrincipale = $imagePrincipale;

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
