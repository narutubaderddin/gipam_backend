<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\AuthorType as Type;
use App\Entity\Person;
use App\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => true])
            ->add('lastName', TextType::class, ['required' => true])
            ->add('active', BooleanType::class, ['required' => true])
            ->add('type', EntityType::class, [
                    'class' => Type::class,
                    'choice_label' => 'id'
                ]
            )
            ->add('people', CollectionType::class, array(
                'entry_type' => EntityType::class,
                'entry_options' => array('class' => Person::class,
                    'label' => false,
                    'choice_label' => 'id',
                ),
                'allow_delete' => true,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Author::class,
        ]);
    }
}
