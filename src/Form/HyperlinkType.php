<?php

namespace App\Form;

use App\Entity\Furniture;
use App\Entity\Hyperlink;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HyperlinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, ['required'=>true])
            ->add('url', UrlType::class, ['required'=>true])
            ->add('furniture',EntityType::class,['class'=>Furniture::class, 'choice_label' => 'id','required'=>false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Hyperlink::class,
        ]);
    }
}
