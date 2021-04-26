<?php

namespace App\Entity;

use App\Repository\ArtWorkRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArtWorkRepository::class)
 * @ORM\Table(name="oeuvre_art")
 */
class ArtWork extends Furniture
{
    /**
     * @ORM\Column(name="longueur_totale", type="float", nullable=true)
     */
    private $totalLength;

    /**
     * @ORM\Column(name="largeur_totale", type="float", nullable=true)
     */
    private $totalWidth;

    /**
     * @ORM\Column(name="hauteur_totale", type="float", nullable=true)
     */
    private $totalHeight;

    /**
     * @ORM\Column(name="signature_inscription", type="string", length=255, nullable=true)
     */
    private $registrationSignature;

    /**
     * @ORM\Column(name="mots_descriptifs", type="string", length=255, nullable=true)
     */
    private $descriptiveWords;

    /**
     * @ORM\Column(name="valeur_assurance", type="integer", nullable=true)
     */
    private $insuranceValue;

    /**
     * @ORM\Column(name="date_valeur_assurance", type="datetime", nullable=true)
     */
    private $insuranceValueDate;

    /**
     * @ORM\Column(name="date_depot", type="datetime", nullable=true)
     */
    private $depositDate;

    /**
     * @ORM\Column(name="numero_arret", type="integer", nullable=true)
     */
    private $stopNumber;

    /**
     * @ORM\Column(name="autres_inscription", type="string", length=255, nullable=true)
     */
    private $otherRegistrations;

    public function getTotalLength(): ?float
    {
        return $this->totalLength;
    }

    public function setTotalLength(?float $totalLength): self
    {
        $this->totalLength = $totalLength;

        return $this;
    }

    public function getTotalWidth(): ?float
    {
        return $this->totalWidth;
    }

    public function setTotalWidth(?float $totalWidth): self
    {
        $this->totalWidth = $totalWidth;

        return $this;
    }

    public function getTotalHeight(): ?float
    {
        return $this->totalHeight;
    }

    public function setTotalHeight(?float $totalHeight): self
    {
        $this->totalHeight = $totalHeight;

        return $this;
    }

    public function getRegistrationSignature(): ?string
    {
        return $this->registrationSignature;
    }

    public function setRegistrationSignature(?string $registrationSignature): self
    {
        $this->registrationSignature = $registrationSignature;

        return $this;
    }

    public function getDescriptiveWords(): ?string
    {
        return $this->descriptiveWords;
    }

    public function setDescriptiveWords(?string $descriptiveWords): self
    {
        $this->descriptiveWords = $descriptiveWords;

        return $this;
    }

    public function getInsuranceValue(): ?int
    {
        return $this->insuranceValue;
    }

    public function setInsuranceValue(?int $insuranceValue): self
    {
        $this->insuranceValue = $insuranceValue;

        return $this;
    }

    public function getInsuranceValueDate(): ?DateTimeInterface
    {
        return $this->insuranceValueDate;
    }

    public function setInsuranceValueDate(DateTimeInterface $insuranceValueDate): self
    {
        $this->insuranceValueDate = $insuranceValueDate;

        return $this;
    }

    public function getDepositDate(): ?DateTimeInterface
    {
        return $this->depositDate;
    }

    public function setDepositDate(?DateTimeInterface $depositDate): self
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

    public function getOtherRegistrations(): ?string
    {
        return $this->otherRegistrations;
    }

    public function setOtherRegistrations(?string $otherRegistrations): self
    {
        $this->otherRegistrations = $otherRegistrations;

        return $this;
    }
}
