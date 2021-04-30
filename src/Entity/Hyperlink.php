<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableEntity;
use App\Repository\HyperlinkRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HyperlinkRepository::class)
 * @ORM\Table(name="lien_hypertexte")
 */
class Hyperlink
{
    use TimestampableEntity;
    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("artwork")
     *
     * @Assert\Url()
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @JMS\Groups("artwork")
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $name;

    /**
     * @JMS\Exclude()
     * @ORM\ManyToOne(targetEntity=Furniture::class, inversedBy="hyperlinks")
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
