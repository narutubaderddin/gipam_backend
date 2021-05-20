<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\AttachmentRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 * @ORM\Table(name="fichier_joint")
 */
class Attachment
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork", "attachment")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $comment;

    /**
     * @JMS\Groups("artwork","art_work_list","art_work_details", "attachment")
     * @ORM\Column(name="lien", type="string", length=255)
     */
    private $link;

    /**
     * @JMS\Exclude()
     *
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="attachments")
     * @ORM\JoinColumn(name="objet_mobilier_id", referencedColumnName="id")
     */
    private $furniture;

    /**
     * @JMS\Groups("artwork","art_work_details", "attachment")
     *
     * @Assert\Valid()
     *
     * @ORM\ManyToOne(targetEntity=AttachmentType::class, inversedBy="attachments")
     * @ORM\JoinColumn(name="type_fichier_joint_id", referencedColumnName="id")
     */
    private $attachmentType;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link): self
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
