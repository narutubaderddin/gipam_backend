<?php

namespace App\Form;

use App\Entity\ArtWork;
use App\Entity\Author;
use App\Entity\Denomination;
use App\Entity\DepositStatus;
use App\Entity\Era;
use App\Entity\Field;
use App\Entity\Furniture;
use App\Entity\MaterialTechnique;
use App\Entity\Photography;
use App\Entity\Status;
use App\Entity\Style;
use App\Form\Type\BooleanType;
use App\Services\FurnitureService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ArtWorkType extends AbstractType
{
    const PROPERTY_STATUS = 'property';
    const DEPOSIT_STATUS = 'deposit';
    const STATUS = [
        self::DEPOSIT_STATUS => DepositStatusType::class,
        self::PROPERTY_STATUS => PropertyStatusType::class
    ];

    protected $furnitureService;
    protected $attributes = [];
    protected $statusType =  self::DEPOSIT_STATUS;

    /**
     * ArtWorkType constructor.
     * @param FurnitureService $furnitureService
     */
    public function __construct(FurnitureService $furnitureService)
    {
        $this->furnitureService = $furnitureService;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['status']) &&
            array_key_exists($options['status'], self::STATUS)
        ){
            $this->statusType = self::STATUS[$options['status']];
        }
        $builder
            ->add('title',TextType::class)
            ->add('denomination', EntityType::class, [
                    'class' => Denomination::class,
                    'choice_label' => 'id',
                ]
            )
            ->add('field', EntityType::class, [
                    'class' => Field::class,
                    'choice_label' => 'id',
                ]
            )
            ->add('isCreated', BooleanType::class);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $artWork = $event->getData();
            $form = $event->getForm();
            if (!$artWork) {
                return;
            }
            $denominationId = $artWork['denomination']?? null;
            $fieldId = $artWork['field']?? null;
            $this->attributes = $this->furnitureService
                ->getAttributesByDenominationIdAndFieldId($denominationId, $fieldId);

            if (in_array('length', $this->attributes)) {
                $form->add('length',TextType::class);
            } else {
                unset($artWork['length']);
                $event->setData($artWork);
            }
            if (in_array('width', $this->attributes)) {
                $form->add('width',TextType::class);
            } else {
                unset($artWork['width']);
                $event->setData($artWork);
            }
            if (in_array('height', $this->attributes)) {
                $form->add('height',TextType::class);
            } else {
                unset($artWork['height']);
                $event->setData($artWork);
            }
            if (in_array('depth', $this->attributes)) {
                $form->add('depth',TextType::class);
            } else {
                unset($artWork['depth']);
                $event->setData($artWork);
            }
            if (in_array('diameter', $this->attributes)) {
                $form->add('diameter',TextType::class);
            } else {
                unset($artWork['diameter']);
                $event->setData($artWork);
            }
            if (in_array('weight', $this->attributes)) {
                $form->add('weight',TextType::class);
            } else {
                unset($artWork['weight']);
                $event->setData($artWork);
            }
            if (in_array('numberOfUnit', $this->attributes)) {
                $form->add('numberOfUnit',IntegerType::class);
            } else {
                unset($artWork['numberOfUnit']);
                $event->setData($artWork);
            }
            if (in_array('creationDate', $this->attributes)) {
                $form->add('creationDate', DateTimeType::class, ['widget' => 'single_text']);
            } else {
                unset($artWork['creationDate']);
                $event->setData($artWork);
            }
            if (in_array('authors', $this->attributes)) {
                $form->add('authors', CollectionType::class, array(
                    'entry_type' => EntityType::class,
                    'entry_options' => array(   'class'=> Author::class,
                        'label'=>false,
                        'choice_label'=>'id',
                    ),
                    'allow_delete' =>true,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => false,
                ));
            } else {
                unset($artWork['authors']);
                $event->setData($artWork);
            }
            if (in_array('era', $this->attributes)) {
                $form->add('era', EntityType::class, [
                        'class' => Era::class,
                        'choice_label' => 'id',
                    ]
                );
            } else {
                unset($artWork['era']);
                $event->setData($artWork);
            }
            if (in_array('style', $this->attributes)) {
                $form->add('style', EntityType::class, [
                        'class' => Style::class,
                        'choice_label' => 'id',
                    ]
                );
            } else {
                unset($artWork['style']);
                $event->setData($artWork);
            }
            if (in_array('materialTechnique', $this->attributes)) {
                $form->add('materialTechnique', CollectionType::class, array(
                    'entry_type' => EntityType::class,
                    'entry_options' => array( 'class'=> MaterialTechnique::class,
                        'label'=>false,
                        'choice_label'=>'id',
                    ),
                    'allow_delete' =>true,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => false,
                ));
            } else {
                unset($artWork['materialTechnique']);
                $event->setData($artWork);
            }
            if (in_array('visible', $this->attributes)) {
                $form->add('visible',BooleanType::class, ['required'=>true]);
            } else {
                unset($artWork['visible']);
                $event->setData($artWork);
            }
            if (in_array('parent', $this->attributes)) {
                $form->add('parent', EntityType::class, [
                        'class' => Furniture::class,
                        'choice_label' => 'id',
                    ]
                );
            } else {
                unset($artWork['parent']);
                $event->setData($artWork);
            }
            if (in_array('totalLength', $this->attributes)) {
                $form->add('totalLength',NumberType::class);
            } else {
                unset($artWork['totalLength']);
                $event->setData($artWork);
            }
            if (in_array('totalWidth', $this->attributes)) {
                $form->add('totalWidth',NumberType::class);
            } else {
                unset($artWork['totalWidth']);
                $event->setData($artWork);
            }
            if (in_array('totalHeight', $this->attributes)) {
                $form->add('totalHeight',NumberType::class);
            } else {
                unset($artWork['totalHeight']);
                $event->setData($artWork);
            }
            if (in_array('status', $this->attributes)) {
                    $form->add('status',$this->statusType);
            } else {
                unset($artWork['status']);
                $event->setData($artWork);
            }
            if (in_array('hyperlinks', $this->attributes)) {
                $form->add('hyperlinks', CollectionType::class, array(
                    'entry_type' => HyperlinkType::class,
                    'required'=>false,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => true,
                ));
            } else {
                unset($artWork['hyperlinks']);
                $event->setData($artWork);
            }
            if (in_array('attachments', $this->attributes)) {
                $form->add('attachments', CollectionType::class, array(
                    'entry_type' => AttachmentType::class,
                    'required'=>false,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => true,
                ));
            } else {
                unset($artWork['attachments']);
                $event->setData($artWork);
            }
            if (in_array('photographies', $this->attributes)) {
                $form->add('photographies', CollectionType::class, array(
                    'entry_type' => PhotographyType::class,
                    'allow_add' => true,
                    'prototype' => true,
                    'by_reference' => true,
                ));

            } else {
                unset($artWork['photographies']);
                $event->setData($artWork);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => ArtWork::class,
            'status' => self::DEPOSIT_STATUS,
            'allow_extra_fields' => true
        ]);
    }
}
