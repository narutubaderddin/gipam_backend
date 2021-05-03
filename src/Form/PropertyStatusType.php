<?php

namespace App\Form;

use App\Entity\EntryMode;
use App\Entity\PropertyStatus;
use App\Entity\PropertyStatusCategory;
use App\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entryDate',DateTimeType::class, ['widget' => 'single_text'])
            ->add('marking',TextType::class)
            ->add('entryMode', EntityType::class, [
                'class' => EntryMode::class,
                'choice_label' => 'id',
                ]
            )
            ->add('category', EntityType::class, [
                    'class' => PropertyStatusCategory::class,
                    'choice_label' => 'id',
                ]
            )
            ->add('propOnePercent',BooleanType::class)
            ->add('registrationSignature',TextType::class)
            ->add('descriptiveWords',TextType::class)
            ->add('insuranceValue',IntegerType::class)
            ->add('insuranceValueDate',DateTimeType::class, ['widget' => 'single_text'])
            ->add('otherRegistrations',TextType::class)
            ->add('description',TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => PropertyStatus::class,
        ]);
    }
}
