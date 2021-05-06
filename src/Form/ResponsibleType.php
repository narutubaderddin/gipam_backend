<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\Responsible;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ResponsibleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => true])
            ->add('lastName',TextType::class, ['required' => true])
            ->add('phone',TextType::class)
            ->add('fax',TextType::class)
            ->add('mail',EmailType::class)
            ->add('login',TextType::class)
            ->add('startDate', DateTimeType::class, ['required' => true, 'widget' => 'single_text'])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('buildings', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Building::class,
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
            'data_class' => Responsible::class
        ]);
    }
}
