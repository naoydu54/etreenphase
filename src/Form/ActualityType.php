<?php

namespace App\Form;

use App\Entity\Actuality;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de la campagne'
                ],
                'label' => 'Nom de l\'actualité'

            ])->add('content', CKEditorType::class, [
                'label' => 'Contenu',
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
            ->add('dateStart', DateType::class, [

                'label' => 'date de début',
                'widget' => 'single_text',



            ])
            ->add('dateEnd', DateType::class, [
                'label' => 'date de fin',
                'widget' => 'single_text',
            ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label' => 'Enregistrer'

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actuality::class,
        ]);
    }
}
