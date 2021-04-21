<?php

namespace App\Form;

use App\Entity\ReportSubType;
use App\Entity\ReportType;
use App\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportSubTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, ['required' => true])
            ->add('reportType', EntityType::class, [
                    'class' => ReportType::class,
                    'choice_label' => 'id',
                ])
            ->add('active', BooleanType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => ReportSubType::class,
        ]);
    }
}
