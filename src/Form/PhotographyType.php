<?php

namespace App\Form;

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
use Symfony\Component\Validator\Constraints as Assert;

class PhotographyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagePreview', FileType::class)
            ->add('date',DateTimeType::class, ['widget' => 'single_text', 'required'=>true])
            ->add('photographyType', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
                ]
            )
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
