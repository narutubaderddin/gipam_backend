<?php

namespace App\Entity;

use App\Repository\ArtWorkRepository;
use App\Services\ArtWorkService;
use Doctrine\Common\Persistence\ObjectManagerAware;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=ArtWorkRepository::class)
 * @ORM\Table(name="oeuvre_art")
 * @ORM\HasLifecycleCallbacks()
 */
class ArtWork extends Furniture implements ObjectManagerAware
{

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="longueur_totale", type="float", nullable=true)
     */
    private $totalLength;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="largeur_totale", type="float", nullable=true)
     */
    private $totalWidth;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="hauteur_totale", type="float", nullable=true)
     */
    private $totalHeight;

    /**
     * @var EntityManager
     */
    private $entityManager;



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
    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("communes")
     * @JMS\Groups("art_work_list")
     */
    public function getCommunesData(){
        $results = $this->entityManager->getRepository(ArtWork::class)
            ->getLocationData($this,'commune');
        return is_array($results)? $results:null;
    }
    /**
     * @JMS\VirtualProperty()
     * @JMS\SerializedName("buildings")
     * @JMS\Groups("art_work_list")
     */
    public function getbuildingsData(){
        $results = $this->entityManager->getRepository(ArtWork::class)
            ->getLocationData($this,'building');
        return is_array($results)? $results:null;
    }

    public function injectObjectManager(ObjectManager $objectManager, ClassMetadata $classMetadata)
    {
        $this->entityManager = $objectManager;
    }
}
