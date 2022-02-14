<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeByCseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Nom de famille'
            ])
            ->add('firstName', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Prénom'
            ])

            ->add('email', EmailType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'email',
                'required'=>false
            ])
            ->add('activity', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Secteur d\'activité'
            ])

            ->add('send', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>'Enregistrer'

            ])
        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
