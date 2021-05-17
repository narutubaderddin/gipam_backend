<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\PhotographyTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhotographyTypeRepository::class)
 * @ORM\Table(name="type_photographie")
 * @UniqueEntity("type", repositoryMethod="iFindBy", message="Un type photographie avec ce libellé existe déjà!")
 */
class PhotographyType
{
    use TimestampableEntity;

    public const TYPE = [
        'principle' => 'Photo Principale',
        'detail' => 'Photo de détail',
    ];
    /**
     * @JMS\Groups("artwork", "id", "photography_type")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("artwork", "photography_type")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @JMS\Groups("photography_type")
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $active = true;

    /**
     * @JMS\Exclude()
     * @ORM\OneToMany(targetEntity=Photography::class, mappedBy="photographyType")
     */
    private $photographies;

    public function __construct()
    {
        $this->photographies = new ArrayCollection();
    }

    public function isActive(): ?bool
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
     * @return Collection|Photography[]
     */
    public function getPhotographies(): Collection
    {
        return $this->photographies;
    }

    public function addPhotography(Photography $photography): self
    {
        if (!$this->photographies->contains($photography)) {
            $this->photographies[] = $photography;
            $photography->setPhotographyType($this);
        }

        return $this;
    }

    public function removePhotography(Photography $photography): self
    {
        if ($this->photographies->removeElement($photography)) {
            // set the owning side to null (unless already changed)
            if ($photography->getPhotographyType() === $this) {
                $photography->setPhotographyType(null);
            }
        }

        return $this;
    }
}
