<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\CompanyHasAddress;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

//            ->add('name', TextType::class, [
//                'attr'=>[
//                    'class'=>'form-control'
//                ],
//                'label'=>"Nom"
//            ])
//            ->add('firstname', TextType::class, [
//                'attr'=>[
//                    'class'=>'form-control'
//                ],
//                'label'=>"Prénom"
//            ])
//            ->add('numberphone', TextType::class,[
//                'attr'=>[
//                    'class'=>'form-control'
//                ],
//                'label'=>"Numéro de téléphone"
//            ])
//            ->add('email', EmailType::class,[
//                'attr'=>[
//                    'class'=>'form-control'
//                ],
//                'label'=>"email"
//            ])

            ->add('address', EntityType::class, [
                'mapped'=>false,
                'multiple'=>false,
                'required'=>true,
                'label'=>'adresse',
                'by_reference'=>true,
                'class'     => CompanyHasAddress::class,
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Prix CE TTC'
                ],
                'query_builder' => function (EntityRepository $er ) use ($options)  {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.city', 'ASC')
                        ->where('c.company = :company')
                        ->setParameter('company', $options['company'] );
                },

                'choice_label' => function ($companyHasAddress) {
                    return $companyHasAddress->getCity() .' - ' .  $companyHasAddress->getAddress();
                },
                'placeholder' => 'Choisir une adresse',

                'choice_value' => 'id',


            ])

//            ->add('activity', TextType::class, [
//                'attr'=>[
//                    'class'=>'form-control'
//                ],
//                'label'=>"Secteur d'activité"
//            ])
            ->add('paiement', ChoiceType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'choices'  => [
                    'cheque' => 'chèque à l’ordre Alliance Manufactures de France à envoyer à l’adresse suivante: 28 rue des clous 88350 PARGNY SOUS MUREAU',
                    'virement' => 'virement mettre en référence: nom salarié concerné, entreprise, commande CSE De Buyer. Info du RIB( envoyé par mail)',

                ],
            ])
            ->add('Envoyer', SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>'Envoyer au CSE'
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
            'company' => null,

        ]);
    }
}
