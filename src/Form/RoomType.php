<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\Establishment;
use App\Entity\EstablishmentType as Type;
use App\Entity\Ministry;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('level',TextType::class, ['required'=>true])
            ->add('reference',TextType::class, ['required'=>true])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'required' => true])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('building', EntityType::class, [
                    'class' => Building::class,
                    'choice_label' => 'id',
                    'required' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Room::class,
        ]);
    }
}
