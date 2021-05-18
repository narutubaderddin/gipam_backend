<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\PhotographyType as Type;
use App\Entity\Photography;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class PhotographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagePreview', FileType::class,['data_class'=>null,
                'required'=>false,
                'empty_data'=>'',
                'constraints'=>[
                    new Image([
                        'maxSize'=>"25M",
                        'maxSizeMessage'=>'File should not be heavier than 25 M'
                    ])
                ]
            ])
            ->add('date',DateTimeType::class, ['widget' => 'single_text', 'required'=>false])
            ->add('photographyType', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
                ]
            )
            ->add('furniture',EntityType::class,['class'=>Furniture::class, 'choice_label' => 'id','required'=>false])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event){
                $entity = $event->getData();
                $form = $event->getForm();
                if (!$entity) {
                    return;
                }
                if (isset($form['imagePreview']) && ($form['imagePreview']->getData() instanceof UploadedFile)){
                    $entity->setImageName($form['imagePreview']->getData()->getClientOriginalName());
                    $event->setData($entity);
                }elseif(isset($form['imagePreview']) && is_null($entity->getImageName())){
                    throw new \Exception('imagePreview should be file');
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Photography::class,
        ]);
    }
}
