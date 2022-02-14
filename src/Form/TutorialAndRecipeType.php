<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\TutorialAndRecipe;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TutorialAndRecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Nom de la page'
                ],
                'label'=>'Nom de la page'

            ])
            ->add('image', FileType::class, [
                    'label' => 'Image de couverture',

                    'mapped' => false,

                    'required' => false,

                ]
            )

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
            ->add('sugar', ChoiceType::class, [
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'  => [
                    'sucré' => true,
                    'salé' => false,
                ],
                'label'=>'Type de recette',
                'required'=>false

            ])

            ->add('tutorial', ChoiceType::class, [
                'attr'=>[
                    'class'=>'form-control',
                ],
                'choices'  => [
                    'tutoriel' => true,
                    'recette' => false,
                ],
                'label'=>'Type de recette',
                'required'=>false

            ])


        ->add('products', EntityType::class, [
            // looks for choices from this entity
            'class' => Product::class,
            'multiple'=>true,
            'expanded' => false,
            'by_reference' => true,


            'label'=>'Produits associée',

            'choice_label' => function ( Product $product) {
                return $product->getName() . ' - '  . $product->getMenus()->first()->getName()  ;

            },

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
            'data_class' => TutorialAndRecipe::class,
        ]);
    }
}
