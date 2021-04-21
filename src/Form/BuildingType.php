<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\Commune;
use App\Entity\Responsible;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required' => true])
            ->add('address', TextType::class)
            ->add('distrib', TextType::class)
            ->add('cedex', TextType::class)
            ->add('startDate', DateTimeType::class, ['required' => true, 'widget' => 'single_text'])
            ->add('disappearanceDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('commune', EntityType::class, [
                'class' => Commune::class,
                'choice_label' => 'id',
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'id',
            ])
            ->add('responsibles', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Responsible::class,
                    'label' => false,
                    'choice_label' => 'id',
                ],
                'allow_delete' => true,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Building::class,
        ]);
    }
}
