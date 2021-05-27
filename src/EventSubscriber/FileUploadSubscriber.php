<?php


namespace App\EventSubscriber;


use App\Entity\Attachment;
use App\Entity\Photography;
use App\Services\FileUploader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadSubscriber implements EventSubscriber
{
    private $uploader;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * FileUploadSubscriber constructor.
     * @param FileUploader $uploader
     */
    public function __construct(FileUploader $uploader,EntityManagerInterface $entityManager)
    {
        $this->uploader = $uploader;
        $this->entityManager = $entityManager;

    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param $entity
     */
    private function uploadFile($entity)
    {
        $fileName=null;
        if ($entity instanceof Photography){
            $file = $entity->getImagePreview();
            $fileIncrement=$this->entityManager->getRepository(Photography::class)->getMaxIncrement();
            $fileIncrement= intval($fileIncrement['value']);
            $fileName= $entity->getFurniture()->getId().'-'.($fileIncrement+1);
        }elseif ($entity instanceof Attachment){
            $file = $entity->getLink();
        }else{
            return;
        }

        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file,$fileName);
        $path =$fileName;
        if ($entity instanceof Photography){
            $entity->setImagePreview($path);
        }
        if ($entity instanceof Attachment){
            $entity->setLink($path);
        }
    }
}