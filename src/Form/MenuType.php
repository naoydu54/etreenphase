<?php

namespace App\Form;

use App\Entity\Menu;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Nom du menu'
                ],
                'label'=>'Nom du menu'

            ])
//            ->add('root', EntityType::class, [
//        'class'=>Menu::class,
//        'choice_label'=>'name',
//        'required'=>false,
//        'attr'=>[
//            'class'=>'form-control'
//        ]

//    ])
            ->add('parent', EntityType::class, [
                'class'=>Menu::class,
                'choice_label'=>'name',
                'required'=>false,
                'attr'=>[
                    'class'=>'form-control'
                ]

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

            ->add('image', FileType::class, [
                    'label' => 'Image de couverture',

                    'mapped' => false,

                    'required' => false,

                ]
            )
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
            'data_class' => Menu::class,
        ]);
    }
}
