<?php

namespace App\Form;

use App\Entity\LocationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, ['required' => true])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'required' => true])
            ->add('disappearanceDate', DateTimeType::class, ['widget' => 'single_text']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => LocationType::class,
        ]);
    }
}
