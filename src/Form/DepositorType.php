<?php

namespace App\Form;

use App\Entity\Depositor;
use App\Entity\DepositType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepositorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required' => true])
            ->add('acronym', TextType::class, ['required' => true])
            ->add('mail', TextType::class, ['required' => true])
            ->add('phone', TextType::class, ['required' => true])
            ->add('fax', TextType::class, ['required' => true])
            ->add('address', TextType::class, ['required' => true])
            ->add('city', TextType::class, ['required' => true])
            ->add('department', TextType::class, ['required' => true])
            ->add('distrib', TextType::class, ['required' => true])
            ->add('contact', TextType::class, ['required' => true])
            ->add('comment', TextareaType::class, ['required' => true])
            ->add('depositType', EntityType::class, [
                'class' => DepositType::class,
                'choice_label' => 'id',
                'required' => true
            ])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'required' => true])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Depositor::class,
        ]);
    }
}
