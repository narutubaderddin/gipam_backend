<?php

namespace App\Services;

use App\Entity\Denomination;
use App\Entity\Field;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class FurnitureService
 * @package App\Services
 */
class FurnitureService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * FurnitureService constructor.
     * @param EntityManagerInterface $em
     * @param array $attributes
     */
    public function __construct(EntityManagerInterface $em, array $attributes)
    {
        $this->em = $em;
        $this->attributes = $attributes;
    }

    /**
     * @param int|null $denominationId
     * @param int|null $fieldId
     * @return array|mixed
     */
    public function getAttributesByDenominationIdAndFieldId(?int $denominationId, ?int $fieldId)
    {
        if ($denominationId){
            $denomination = $this->em->getRepository(Denomination::class)->find($denominationId);
            if (!$denomination instanceof Denomination){
                throw new NotFoundHttpException('Denomination not Found');
            }
            if (array_key_exists($denomination->getLabel(), $this->attributes['denomination'])){
                return $this->attributes['denomination'][$denomination->getLabel()];
            }
        }
        if ($fieldId){
            $field = $this->em->getRepository(Field::class)->find($fieldId);
            if (!$field instanceof Field){
                throw new NotFoundHttpException('Field not Found');
            }
            if (array_key_exists($field->getLabel(), $this->attributes['field'])){
                return $this->attributes['field'][$field->getLabel()];
            }
        }
        return $this->getDefaultAttributes();
    }

    /**
     * @return array
     */
    public function getDefaultAttributes():array
    {
        return $this->attributes['default'];
    }
}