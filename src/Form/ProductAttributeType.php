<?php

namespace App\Form;

use App\Entity\Attribute;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attribute', EntityType::class, [
                'class' => Attribute::class,
                'choice_label' => 'name',
                'required' => false,
                'empty_data' => '',
            ]);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
//            dump($event);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'data_class' => Attribute::class,
//        ]);
    }
}
