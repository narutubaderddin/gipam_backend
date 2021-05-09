<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\AttachmentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AttachmentTypeRepository::class)
 * @ORM\Table(name="type_fichier_joint")
 * @UniqueEntity("type", repositoryMethod="iFindBy", message="Un type fichier joint avec ce libellé existe déjà!")
 */
class AttachmentType
{
    use TimestampableEntity;

    /**
     * @JMS\Groups("artwork", "id")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("artwork", "attachment_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @JMS\Groups("attachment_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $active = true;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="attachmentType")
     */
    private $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setAttachmentType($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getAttachmentType() === $this) {
                $attachment->setAttachmentType(null);
            }
        }

        return $this;
    }
}
