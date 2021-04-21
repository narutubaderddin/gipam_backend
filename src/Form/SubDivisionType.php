<?php

namespace App\Form;

use App\Entity\SubDivision;
use App\Entity\Establishment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubDivisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class, ['required'=>true])
            ->add('acronym',TextType::class, ['required'=>true])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'required'=>true])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('establishment', EntityType::class, [
                    'class' => Establishment::class,
                    'choice_label' => 'id',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => SubDivision::class,
        ]);
    }
}
