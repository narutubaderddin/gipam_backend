<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 * @ORM\Table(name="fichier_joint")
 */
class Attachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date_creation", type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(name="lien", type="string", length=255)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="attachments")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")
     */
    private $furniture;

    /**
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity=attachmentType::class, inversedBy="attachments")
     * @ORM\JoinColumn(name="type_fichier_joint_id", referencedColumnName="id", nullable=false)
     */
    private $attachmentType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getFurniture(): ?Furniture
    {
        return $this->furniture;
    }

    public function setFurniture(?Furniture $furniture): self
    {
        $this->furniture = $furniture;

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

    public function getAttachmentType(): ?attachmentType
    {
        return $this->attachmentType;
    }

    public function setAttachmentType(?attachmentType $attachmentType): self
    {
        $this->attachmentType = $attachmentType;

        return $this;
    }
}
