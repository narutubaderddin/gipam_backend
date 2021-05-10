<?php

namespace App\EventSubscriber;

use App\Entity\Photography;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class SerializerEventSubscriber implements EventSubscriberInterface
{
    protected $manager;
    protected $projectDir;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->projectDir = $kernel->getProjectDir();
    }

    public function onPreSerializePhotography(ObjectEvent $event)
    {
        $photography = $event->getObject();
        if (!$photography instanceof Photography) {
            return;
        }
        $path = preg_replace('/\\\\/', "/", '\\uploads\\' . $photography->getImagePreview());
        $photography->setImagePreview($path);
    }

    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => Events::PRE_SERIALIZE,
                'method' => 'onPreSerializePhotography',
                'class' => 'App\Entity\Photography',
                'format' => 'json',
                'priority' => 0,
            ));
    }
}