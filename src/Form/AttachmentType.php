<?php

namespace App\Form;

use App\Entity\Attachment;
use App\Entity\AttachmentType as Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment',TextType::class, ['required'=>true])
            ->add('link', FileType::class , ['data_class' => null])
            ->add('attachmentType', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Attachment::class,
        ]);
    }
}
