<?php

namespace App\Entity;

use App\Repository\ArtWorkRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ArtWorkRepository::class)
 * @ORM\Table(name="oeuvre_art")
 */
class ArtWork extends Furniture
{
//    /**
//     * @JMS\Groups("art_work")
//     * @JMS\Type("DateTime<'Y-m-d'>")
//     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
//     */
//    private $creationDate;

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
}
