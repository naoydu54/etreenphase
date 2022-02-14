<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Page;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Nom de la recette ou tutoriel'
                ],
                'label'=>'Nom de la page'

            ])
            ->add('content', CKEditorType::class,[
                'label'=>'Contenu',
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array(
                        'instance' => 'default',
                        'homeFolder' => ''
                    )
                ),
            ])
            ->add('menu', EntityType::class, [
                'class'     => Menu::class,
                'required'=>false,

                'multiple'  => false,
                'query_builder' => function($er) {

                    $er->createQueryBuilder('m')

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
            'data_class' => Page::class,
        ]);
    }
}
