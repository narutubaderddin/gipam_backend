<?php

namespace App\Form;

use App\Entity\Building;
use App\Entity\Commune;
use App\Entity\Departement;
use App\Entity\Responsible;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommuneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, ['required' => true])
            ->add('startDate', DateTimeType::class, ['required' => true, 'widget' => 'single_text'])
            ->add('disappearanceDate', DateTimeType::class, ['widget' => 'single_text'])
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'id',
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Commune::class,
        ]);
    }
}
