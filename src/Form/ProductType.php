<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Product;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('image', FileType::class, [
                    'label' => 'Image de couverture',

                    'mapped' => false,

                    'required' => false,

                ]
            )

        ->add('files', FileType::class, [
            'label' => 'Images diverses',
            'attr' => ['class'=>'dropzone'],

            'mapped' => false,

            'required' => false,
                'multiple' => true,


            ]
        )
            ->add('name', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Nom du produit'
                ],
                'label'=>'Nom du produit'

            ])
            ->add('available', ChoiceType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Produit dismonible'
                ],
                'label'=>'Disponibilité du produit',
                'choices'  => [

                    'Disponible' => true,
                    'Non disponible' => false,
                ],

            ])

            ->add('reference', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'reference produit'
                ],
                'label'=>'reference produit'

            ])
            ->add('pricePublicTTC', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Prix public TTC'
                ],
                'label'=>'Prix publique TTC',
                'required'=>false
            ])
           ->add('priceCeTTC', TextType::class, [
               'attr'=>[
                   'class'=>'form-control',
                   'placeholder'=>'Prix CSE TTC'
               ],
               'label'=>'Prix CSE TTC',
               'required'=>false

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

            ->add('useAndMaintenance', CKEditorType::class,[
                'label'=>'Utiliation et maintenance',
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array(
                        'instance' => 'default',
                        'homeFolder' => ''
                    )
                ),

            ])


            ->add('menus', EntityType::class, [
                'class'     => Menu::class,

                'multiple'  => true,
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


//                'attr'=>[
//                    'class'=>'form-control ',
//
//
//                ],
                'label'=>false
            ])




            ->add('productHasAttributeItems', CollectionType::class, [
                'entry_type' => ProductHatAttributeItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
//                'mapped'=>false,
                'by_reference' => false,
                'allow_delete' => true,
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
            'data_class' => Product::class,
        ]);
    }
}
