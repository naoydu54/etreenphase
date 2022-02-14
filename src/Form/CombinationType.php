<?php

namespace App\Form;

use App\Entity\AttributeItem;
use App\Entity\Combination;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CombinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attributeItem', EntityType::class,[
                'class'=>AttributeItem::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->innerJoin('a.attribute', 'a2')
                        ->orderBy('a.id', 'ASC')
                        ->groupBy('a.value')

                        ;
                },
                'choice_label' => function(AttributeItem $attributeItem){
                    return $attributeItem->getAttribute()->getName() .' ->  '. $attributeItem->getValue();
                },
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Attribut'

            ])

            ->add('pricePublicTTC', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Prix public TTC'
                ],
                'label'=>'Prix publique TTC'
            ])
            ->add('priceCeTTC', TextType::class, [
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Prix CSE TTC'
                ],
                'label'=>'Prix CE TTC'
            ])

            ->add('reference', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'reference produit'
                ],
                'label'=>'reference produit'

            ])
            ->add('send', SubmitType::class, [
                'attr'=>[
                    'class'=>'btn btn-primary combination'
                ],
                'label'=>'Enregistrer'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Combination::class,
        ]);
    }
}
