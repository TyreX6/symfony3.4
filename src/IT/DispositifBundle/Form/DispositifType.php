<?php

namespace IT\DispositifBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use IT\DispositifBundle\Form\ImageType;
use IT\DispositifBundle\Form\ApplicationType;

class DispositifType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('modele', TextType::class, array(
            'attr' => array('class' => 'form-control  validate[required] ',
                'placeholder' => 'Exp: Samsung Note 4',
                'data-errormessage-value-missing' => 'This input is required!')
        ))
            ->add('os', ChoiceType::class, array(
                'attr' => array('class' => 'form-control selectpicker m-b-20 m-r-10',
                    'data-errormessage-value-missing' => 'This input is required!',
                    'data-style' => 'btn-info btn-outline'),
                'choices' => array(
                    'ANDROID' => "ANDROID",
                    'IOS' => "IOS",
                    'Windows' => "WINDOWS",
                    'Linux' => "LINUX"
                ),

            ))
            ->add('categorie', EntityType::class, array(
                    'attr' => array('class' => 'form-control selectpicker m-b-20 m-r-10',
                        'data-errormessage-value-missing' => 'This input is required!',
                        'data-style' => 'btn-info btn-outline'),
                    'class' => 'IT\DispositifBundle\Entity\Categorie',
                    'choice_label' => 'name',
                )
            )
            ->add('etat', ChoiceType::class, array(
                'attr' => array('class' => 'form-control selectpicker m-b-20 m-r-10',
                    'data-errormessage-value-missing' => 'This input is required!',
                    'data-style' => 'btn-info btn-outline'),
                'choices' => array(
                    'Fonctionnel' => "Fonctionnel",
                    'Détruit' => "Détruit",
                    'Perdu' => "Perdu"
                ),

            ))
            ->add('versionOS', TextType::class, array(
                'attr' => array('class' => 'form-control  validate[required] ',
                    'placeholder' => 'Exp: Marshmallow 6.0',
                    'data-errormessage-value-missing' => 'This input is required!')
            ))
            ->add('processeur', TextType::class, array(
                'attr' => array('class' => 'form-control  validate[required] ',
                    'placeholder' => 'Exp: Intel Core I7',
                    'data-errormessage-value-missing' => 'This input is required!')
            ))
            ->add('ram', TextType::class, array(
                'attr' => array('class' => 'form-control  validate[required] ',
                    'data-bts-button-down-class' => 'btn btn-default btn-outline',
                    'data-errormessage-value-missing' => 'This input is required!')
            ))
            ->add('resolution', TextType::class, array(
                'attr' => array('class' => 'form-control  validate[required] ',
                    'placeholder' => 'Exp: 2048x1440',
                    'data-errormessage-value-missing' => 'This input is required!')
            ))
            ->add('numeroSerie', TextType::class, array(
                'attr' => array('class' => 'form-control  validate[required] ',
                    'placeholder' => '295295192599459521',
                    'data-errormessage-value-missing' => 'This input is required!')
            ))
            ->add('apps', CollectionType::class, array(
                'label' => false,
                'required' => false,
                'entry_type' => ApplicationType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'attr' => array(
                    'class' => 'my-apps-selector')
            ));

//        $builder->add('images', CollectionType::class, array(
//            'label' => true,
//            'required'=>false,
//            'entry_type' => ImageType::class,
//            'prototype' => true,
//            'allow_add' => true,
//            'allow_delete' => true,
//            'attr' => array(
//                'class' => 'my-images-selector',)
//            )
//        );
//        $builder->add('save', SubmitType::class, [
//            'label' => 'Send my request',
//        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IT\DispositifBundle\Entity\Dispositif'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_dispositif';
    }


}
