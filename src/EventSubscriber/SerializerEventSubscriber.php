<?php

namespace App\EventSubscriber;

use App\Entity\Furniture;
use App\Entity\Photography;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class SerializerEventSubscriber implements EventSubscriberInterface
{
    protected $manager;
    protected $baseUrl;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->baseUrl = $parameterBag->get('server_base_url');

    }

    public function onPreSerializePhotography(ObjectEvent $event)
    {
        $photography = $event->getObject();
        if (!$photography instanceof Photography) {
            return;
        }
        $uri = strpos($photography->getImagePreview(),'uploads')!==false ?$photography->getImagePreview():'uploads'.DIRECTORY_SEPARATOR.$photography->getImagePreview();
        $path = $this->baseUrl . DIRECTORY_SEPARATOR . $uri;
        $path = preg_replace('/\\\\/', "/", $path);
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
            )
        );
    }
}