<?php

namespace App\Form;

use App\Entity\Person;
use App\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lastName', TextType::class, ['required' => true])
            ->add('firstName', TextType::class, ['required' => true])
            ->add('email', TextType::class, ['required' => true])
            ->add('phone', TextType::class, ['required' => true])
            ->add('website', TextType::class)
            ->add('comment', TextType::class)
            ->add('active', BooleanType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Person::class,
        ]);
    }
}
