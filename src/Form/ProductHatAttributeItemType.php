<?php

namespace App\Form;

use App\Entity\Attribute;
use App\Entity\AttributeItem;
use App\Entity\ProductHasAttributeItem;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductHatAttributeItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


          ->add('attributeItem', EntityType::class,[
              'class'=>AttributeItem::class,
              'query_builder' => function (EntityRepository $er) {
                  return $er->createQueryBuilder('a')
                      ->innerJoin('a.attribute', 'a2')
                      ->orderBy('a2.name', 'ASC')
                      ->groupBy('a.value')

                      ;
              },
              'choice_label' => function(AttributeItem $attributeItem){
              return $attributeItem->getAttribute()->getName() .' ->  '. $attributeItem->getValue();
              }
          ]);


//        ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $attribute = $event->getData();
//            dump($attribute);
//            $form = $event->getForm();
//
//                        $form->add('attributeItem', EntityType::class,[
//                'class'=>AttributeItem::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('a');
//                },
//                'choice_label' => 'value',
//            ]);
//        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductHasAttributeItem::class,
        ]);
    }
}
