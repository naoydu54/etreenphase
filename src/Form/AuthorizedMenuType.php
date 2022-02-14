<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Menu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorizedMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('menus', EntityType::class, [
                'class'     => Menu::class,

                'multiple'  => true,
                'query_builder' => function($er) {

                    $er->createQueryBuilder('m')

                        ->where('m.defaultMenu = :default')
                        ->setParameter('default', 0)
                        ->orderBy('m.root', 'ASC')
                    ;


                },
                'choice_label' => function ( Menu $menus) {
                    return $menus->getName();

                },
                'choice_attr' => function($choice, $key, $value) {


                    return ['data-id' => $choice->getId(),
                        'root'=>$choice->getRoot()->getId(),
                        'parent'=>$choice->getParentId() !== null ? $choice->getParentId() : 'null'
                    ];
                },



                'attr'=>[
                    'class'=>'form-control ',
                    'id'=>'jstree'


                ]
            ])
            ->add('send', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary'
                ],
                'label'=>'Enregistrer'

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
