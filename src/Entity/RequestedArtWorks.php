<?php

namespace App\Entity;

use App\Repository\RequestedArtWorksRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=RequestedArtWorksRepository::class)
 * @ORM\Table(name="oeuvre_demande")
 */
class RequestedArtWorks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\ManyToOne(targetEntity=ArtWork::class, inversedBy="requestedArtWorks")
     * @ORM\JoinColumn(name="oeuvre_id", referencedColumnName="id")
     */
    private $artWork;

    /**
     * @ORM\ManyToOne(targetEntity=Request::class, inversedBy="requestedArtWorks")
     * @ORM\JoinColumn(name="demande_id", referencedColumnName="id")
     */
    private $request;

    /**
     * @JMS\Groups("request_list","request_details")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtWork(): ?ArtWork
    {
        return $this->artWork;
    }

    public function setArtWork(?ArtWork $artWork): self
    {
        $this->artWork = $artWork;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
