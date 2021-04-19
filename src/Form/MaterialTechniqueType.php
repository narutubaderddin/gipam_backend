<?php

namespace App\Form;

use App\Entity\Denomination;
use App\Entity\MaterialTechnique;
use App\Form\Type\BooleanType;
use App\Repository\MaterialTechniqueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialTechniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class, ['required'=>true])
            ->add('type',TextType::class)
            ->add('active', BooleanType::class, ['required'=>true])
            ->add('denominations', CollectionType::class, array(
                'entry_type' => EntityType::class,
                'entry_options' => array(   'class'=> Denomination::class,
                    'label'=>false,
                    'choice_label'=>'id',
                ),
                'allow_delete' =>true,
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => MaterialTechnique::class,
            "allow_extra_fields" => true,
        ]);
    }
}
