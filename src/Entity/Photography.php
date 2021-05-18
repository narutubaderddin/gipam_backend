<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\PhotographyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=PhotographyRepository::class)
 * @ORM\Table(name="photographie")
 */
class Photography
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork", "art_work_details", "art_work","id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("artwork", "art_work","photography", "art_work_details")
     *
     * @ORM\Column(name="nom_image", type="string", length=255)
     */
    private $imageName;

    /**
     * @JMS\Groups("artwork", "art_work_list","art_work","photography", "art_work_details")
     *
     *
     * @ORM\Column(name="apercu_image",type="string", length=255)
     */
    private $imagePreview;

    /**
     * @JMS\Groups("artwork","photography", "art_work")
     *
     * @ORM\Column(name="date",type="datetime")
     */
    private $date;

    /**
     * @JMS\Groups("artwork", "art_work","photography")
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=PhotographyType::class, inversedBy="photographies")
     * @ORM\JoinColumn(name="type_photographie_id", referencedColumnName="id", nullable=false)
     */
    private $photographyType;

    /**
     * @JMS\Exclude()
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="photographies")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id", nullable=false)
     */
    private $furniture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName()
    {
        return $this->imageName;
    }

    public function setImageName($imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImagePreview()
    {
        return $this->imagePreview;
    }

    public function setImagePreview($imagePreview): self
    {
        $this->imagePreview = $imagePreview;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPhotographyType(): ?photographyType
    {
        return $this->photographyType;
    }

    public function setPhotographyType(?photographyType $photographyType): self
    {
        $this->photographyType = $photographyType;

        return $this;
    }

    public function getFurniture(): ?furniture
    {
        return $this->furniture;
    }

    public function setFurniture(?furniture $furniture): self
    {
        $this->furniture = $furniture;

        return $this;
    }
}
