<?php

namespace App\Form;

use App\Entity\Attachment;
use App\Entity\AttachmentType as Type;
use App\Entity\Furniture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment',TextType::class, ['required'=>true])
            ->add('link', FileType::class,[
                'required'=>false,
                'empty_data'=>'',
                'data_class'=>null,
                'constraints'=>[
                    new File([
                        'maxSize'=>"25M",
                        'maxSizeMessage'=>'File should not be heavier than 25 M'
                    ])
                ]
            ])
            ->add('attachmentType', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
                ]
            )
            ->add('furniture',EntityType::class,['class'=>Furniture::class, 'choice_label' => 'id','required'=>false])
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
