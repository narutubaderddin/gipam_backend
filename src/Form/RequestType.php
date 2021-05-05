<?php

namespace App\Form;

use App\Entity\Establishment;
use App\Entity\Request;
use App\Entity\SubDivision;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pieceNumber', TextType::class, ['required' => true])
                ->add('firstName', TextType::class, ['required' => true])
                ->add('lastName', TextType::class, ['required' => true])
                ->add('function', TextType::class, ['required' => true])
                ->add('mail', TextType::class, ['required' => true])
                ->add('phone', TextType::class, ['required' => true])
                ->add('comment', TextType::class)
                /*->add('subDivision', EntityType::class, [
                        'class' => SubDivision::class,
                        'choice_label' => 'id',
                        //'constraints' => [ new Assert\NotBlank()],
                    ]
                    )
                ->add('establishement', EntityType::class, [
                        'class' => Establishment::class,
                        'choice_label' => 'id',
                        //'constraints' => [ new Assert\NotBlank()],
                    ]
                )*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Request::class,
        ]);
    }
}
