<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Entity\EstablishmentType as Type;
use App\Entity\Ministry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstablishmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class, ['required'=>true])
            ->add('acronym',TextType::class, ['required'=>true])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'required' => true])
            ->add('disappearanceDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('ministry', EntityType::class, [
                    'class' => Ministry::class,
                    'choice_label' => 'id',
                ]
            )
            ->add('type', EntityType::class, [
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
            'data_class' => Establishment::class,
        ]);
    }
}
