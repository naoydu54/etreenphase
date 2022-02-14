<?php

namespace App\Form;

use App\Entity\AttributeItem;
use App\Entity\CartElement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('quantity', IntegerType::class, [
                'attr'=>[
                    'class'=>'form-group quantity',
                    'type'=>"number",
                    'value'=>1,
                    'min'=>1,
                    'max'=>99,

                ],
                'label'=>"Quantité",



            ])

//            ->add('attributeItem', EntityType::class,[
//                'class'=>AttributeItem::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('a')
//                        ->innerJoin('a.attribute', 'a2')
//                        ->innerJoin('a.combinations', 'ac')
//
//                        ->orderBy('a2.name', 'ASC')
//                        ->groupBy('a.value')
//
//                        ;
//                },
//                'choice_label' => function(AttributeItem $attributeItem){
//                    return $attributeItem->getAttribute()->getName() .' ->  '. $attributeItem->getValue();
//                },
//                'mapped'=>false
//            ])

            ->add('button', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary',

                ],
                'label'=>"Ajouter au panier",


            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CartElement::class,
        ]);
    }
}
