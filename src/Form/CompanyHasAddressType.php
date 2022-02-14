<?php

namespace App\Form;

use App\Entity\CompanyHasAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyHasAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',

                ],
                'label'=>'Ville',
                'required'=>true

            ])
            ->add('zipCode', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'code postal'
            ])
            ->add('address', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'adresse'
            ])

            ->add('responsableNameCse', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Nom responsable CSE'
            ])

            ->add('numberPhone', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Numéro de téléphone'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompanyHasAddress::class,
        ]);
    }
}
