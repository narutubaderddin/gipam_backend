<?php

namespace App\Form;

use App\Entity\Correspondent;
use App\Entity\Establishment;
use App\Entity\Movement;
use App\Entity\Service;
use App\Entity\SubDivision;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorrespondentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => true])
            ->add('lastName', TextType::class, ['required' => true])
            ->add('phone', TextType::class, ['required' => true])
            ->add('fax', TextType::class)
            ->add('mail', EmailType::class)
            ->add('login', TextType::class)
            ->add('function', TextType::class)
            ->add('startDate', DateTimeType::class, ['required' => true, 'widget' => 'single_text'])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('establishment', EntityType::class, [
                'class' => Establishment::class,
                'choice_label' => 'id',
            ])
            ->add('subDivision', EntityType::class, [
                'class' => SubDivision::class,
                'choice_label' => 'id',
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'id',
            ])
            ->add('movements', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Movement::class,
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
            'data_class' => Correspondent::class
        ]);
    }
}
