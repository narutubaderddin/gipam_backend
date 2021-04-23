<?php

namespace App\Entity;

use App\Repository\PhotographyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotographyRepository::class)
 * @ORM\Table(name="photographie")
 */
class Photography
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nom_image", type="string", length=255)
     */
    private $imageName;

    /**
     * @ORM\Column(name="apercu_image",type="string", length=255)
     */
    private $imagePreview;

    /**
     * @ORM\Column(name="date",type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(name="date_creation",type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(name="date_modification",type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity=photographyType::class, inversedBy="photographies")
     * @ORM\JoinColumn(name="type_photographie_id", referencedColumnName="id", nullable=false)
     */
    private $photographyType;

    /**
     * @ORM\ManyToOne(targetEntity=furniture::class, inversedBy="photographies")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id", nullable=false)
     */
    private $furniture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImagePreview(): ?string
    {
        return $this->imagePreview;
    }

    public function setImagePreview(string $imagePreview): self
    {
        $this->imagePreview = $imagePreview;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

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
