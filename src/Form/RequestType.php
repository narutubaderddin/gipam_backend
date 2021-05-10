<?php

namespace App\Form;

use App\Entity\ArtWork;
use App\Entity\Author;
use App\Entity\Building;
use App\Entity\Establishment;
use App\Entity\Request;
use App\Entity\SubDivision;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pieceNumber', TextType::class, ['required' => true])
                ->add('firstName', TextType::class, ['required' => true])
                ->add('lastName', TextType::class, ['required' => true])
                ->add('function', TextType::class, ['required' => true])
                ->add('mail', TextType::class, ['required' => true])
                ->add('phone', TextType::class, ['required' => true])
                ->add('level', TextType::class, ['required' => true])
                ->add('comment', TextType::class)
            ->add('artWorks', CollectionType::class, array(
                'entry_type' => EntityType::class,
                'entry_options' => array(   'class'=> ArtWork::class,
                    'label'=>false,
                    'choice_label'=>'id',
                ),
                'allow_delete' =>true,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
            ))
                ->add('subDivision', EntityType::class, [
                        'class' => SubDivision::class,
                        'choice_label' => 'id',
                        'constraints' => [ new Assert\NotBlank()],
                    ]
                    )
                ->add('building', EntityType::class, [
                        'class' => Building::class,
                        'choice_label' => 'id',
                        'constraints' => [ new Assert\NotBlank()],
                    ]
                )
                ->add('establishement', EntityType::class, [
                        'class' => Establishment::class,
                        'choice_label' => 'id',
                        'constraints' => [ new Assert\NotBlank()],
                    ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Request::class,
        ]);
    }
}
