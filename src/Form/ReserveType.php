<?php

namespace App\Form;

use App\Entity\Reserve;
use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReserveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class, ['required'=>true])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'required' => true])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('room', EntityType::class, [
                    'class' => Room::class,
                    'choice_label' => 'id',
                    'required' => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Reserve::class,
        ]);
    }
}
