<?php

namespace App\Entity;

use App\Repository\HyperlinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HyperlinkRepository::class)
 * @ORM\Table(name="lien_hypertexte")
 */
class Hyperlink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity=furniture::class, inversedBy="hyperlinks")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id", nullable=false)
     */
    private $furniture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
