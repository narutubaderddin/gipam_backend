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

class PhotographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagePreview', FileType::class,['data_class'=>null,'required'=>false,'empty_data'=>''])
            ->add('date',DateTimeType::class, ['widget' => 'single_text', 'required'=>false, 'format' => 'yyyy-MM-dd'
            ])
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
