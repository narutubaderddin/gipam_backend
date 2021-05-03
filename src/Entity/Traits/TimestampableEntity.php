<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * Trait TimestampableEntity
 * @package App\Entity\Traits
 */
trait TimestampableEntity
{
    /**
     * @var \DateTime
     *
     * @JMS\Exclude()
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_creation", type="datetime",options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @JMS\Exclude()
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="date_modification", type="datetime",options={"default"="CURRENT_TIMESTAMP"})
     */
    protected $updatedAt;

    /**
     * Sets createdAt.
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets updatedAt.
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Returns updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
