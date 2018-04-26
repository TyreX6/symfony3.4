<?php

namespace IT\DispositifBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjecteurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('model', TextType::class, array(
            'attr' => array('class' => 'form-control  validate[required] ',
                'placeholder' => 'Exp: Projecteur Sharp',
                'data-errormessage-value-missing' => 'This input is required!')
        ))
            ->add('category', HiddenType::class, array()
            )
            ->add('status', ChoiceType::class, array(
                'attr' => array(),
                'choices' => array(
                    'Fonctionnel' => "Fonctionnel",
                    'Détruit' => "Détruit",
                    'Perdu' => "Perdu"
                ),

            ))
            ->add('resolution', TextType::class, array(
                'attr' => array('class' => 'form-control  validate[required] ',
                    'placeholder' => 'Exp: 2048x1440',
                    'data-errormessage-value-missing' => 'This input is required!')
            ))
            ->add('barCode', TextType::class, array(
                    'attr' => array('class' => 'form-control  validate[required] ',
                        'placeholder' => 'Exp: 654552626532015614641656',
                        'data-errormessage-value-missing' => 'This input is required!')
                )
            )
            ->add('category')
            ->add('Envoyer', SubmitType::class, array(
                'attr' => array('class' => 'btn btn-primary'),
            ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IT\DispositifBundle\Entity\Projecteur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_projecteur';
    }


}
