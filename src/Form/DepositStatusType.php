<?php

namespace App\Form;

use App\Entity\Depositor;
use App\Entity\DepositStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class DepositStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type',TextType::class, ['required'=>true])
            ->add('startDate',DateTimeType::class, ['widget' => 'single_text'])
            ->add('endDate',DateTimeType::class, ['widget' => 'single_text'])
            ->add('comment',TextType::class)
            ->add('inventoryNumber',TextType::class)
            ->add('depositor', EntityType::class, [
                'class' => Depositor::class,
                'choice_label' => 'id',
                ]
            )
            ->add('depositDate',DateTimeType::class, [
                'widget' => 'single_text',
                'required'=>false, 'format' => 'yyyy-MM-dd'
                ]
            )
            ->add('stopNumber',IntegerType::class, [
                'required'=>false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => DepositStatus::class,
        ]);
    }
}
