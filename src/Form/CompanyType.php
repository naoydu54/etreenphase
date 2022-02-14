<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Nom de l\'entreprise'
            ])

            ->add('legalForm', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Forme juridique'
            ])
            ->add('siret', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Siret'
            ])
            ->add('siretConcerned', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Site concerné'
            ])

            ->add('nafApe', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'NAF / APE'
            ])

            ->add('effective', IntegerType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Effectif'
            ])

            ->add('firstname', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Prénom'
            ])

            ->add('lasname', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Nom'
            ])

            ->add('numberPhone', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Numéro professionnel'
            ])
            ->add('dayOfPermanence', TextType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Jour de permanence'
            ])

            ->add('email', EmailType::class, [
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'E-mail'
            ])

            ->add('companyHasAdresses', CollectionType::class, [
                // each entry in the array will be an "email" field
                'entry_type' => CompanyHasAddressType::class,
                // these options are passed to each "email" type


                'entry_options' => [
                    'label' => false,
                    'required'=>true,
//                    'allow_add' => true,
                    ],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'required'=>true
            ])



            ->add('send', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-success ',

                ],
                'label'=>'valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
