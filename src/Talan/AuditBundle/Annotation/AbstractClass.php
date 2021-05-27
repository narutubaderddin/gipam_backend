<?php


namespace App\Talan\AuditBundle\Annotation;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class AbstractClass extends  Annotation
{
    public $children;


}