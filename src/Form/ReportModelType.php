<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\AuthorType as Type;
use App\Entity\Field;
use App\Entity\Person;
use App\Entity\ReportModel;
use App\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportModelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => true])
            ->add('active', BooleanType::class, ['required' => true])
            ->add('fields', CollectionType::class, array(
                'entry_type' => EntityType::class,
                'entry_options' => array('class' => Field::class,
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
            'data_class' => ReportModel::class,
        ]);
    }
}
