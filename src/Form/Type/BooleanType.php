<?php

namespace App\Form\Type;

use App\Form\DataTransformer\BooleanTypeToBooleanTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BooleanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new BooleanTypeToBooleanTransformer($options['true_values'], $options['false_values']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'compound' => false,
            'true_values' => array(1, '1', true, 'true'),
            'false_values' => array(0, '0', false, 'false'),
        ));
    }
}