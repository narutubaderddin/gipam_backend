<?php

namespace App\Entity;

use App\Repository\OeuvreArtRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OeuvreArtRepository::class)
 */
class OeuvreArt extends ObjetMobilier
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longueurTotale;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $largeurTotale;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $hauteurTotale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $signatureInscription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $motsDescriptifs;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $valeurAssurance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateValeurAssurance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numeroArret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $autresInscription;

    public function getDateCreation(): ?DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getLongueurTotale(): ?float
    {
        return $this->longueurTotale;
    }

    public function setLongueurTotale(?float $longueurTotale): self
    {
        $this->longueurTotale = $longueurTotale;

        return $this;
    }

    public function getLargeurTotale(): ?float
    {
        return $this->largeurTotale;
    }

    public function setLargeurTotale(?float $largeurTotale): self
    {
        $this->largeurTotale = $largeurTotale;

        return $this;
    }

    public function getHauteurTotale(): ?float
    {
        return $this->hauteurTotale;
    }

    public function setHauteurTotale(?float $hauteurTotale): self
    {
        $this->hauteurTotale = $hauteurTotale;

        return $this;
    }

    public function getSignatureInscription(): ?string
    {
        return $this->signatureInscription;
    }

    public function setSignatureInscription(?string $signatureInscription): self
    {
        $this->signatureInscription = $signatureInscription;

        return $this;
    }

    public function getMotsDescriptifs(): ?string
    {
        return $this->motsDescriptifs;
    }

    public function setMotsDescriptifs(?string $motsDescriptifs): self
    {
        $this->motsDescriptifs = $motsDescriptifs;

        return $this;
    }

    public function getValeurAssurance(): ?int
    {
        return $this->valeurAssurance;
    }

    public function setValeurAssurance(?int $valeurAssurance): self
    {
        $this->valeurAssurance = $valeurAssurance;

        return $this;
    }

    public function getDateValeurAssurance(): ?DateTimeInterface
    {
        return $this->dateValeurAssurance;
    }

    public function setDateValeurAssurance(DateTimeInterface $dateValeurAssurance): self
    {
        $this->dateValeurAssurance = $dateValeurAssurance;

        return $this;
    }

    public function getDateDepot(): ?DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(?DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getNumeroArret(): ?string
    {
        return $this->numeroArret;
    }

    public function setNumeroArret(?string $numeroArret): self
    {
        $this->numeroArret = $numeroArret;

        return $this;
    }

    public function getAutresInscription(): ?string
    {
        return $this->autresInscription;
    }

    public function setAutresInscription(?string $autresInscription): self
    {
        $this->autresInscription = $autresInscription;

        return $this;
    }
}
