<?php

namespace IT\ReservationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReglesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('limDureeReservation', ChoiceType::class, array(
            'attr' => array('class' => 'col-md-1 form-control selectpicker m-b-20 m-r-10',
                'data-errormessage-value-missing' => 'This input is required!',
                'data-style' => 'btn-info btn-outline'),
            'choices' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4
            ),

        ))
            ->add('nbrLimiteParJour', ChoiceType::class, array(
                'attr' => array('class' => 'col-md-1 form-control selectpicker m-b-20 m-r-10',
                    'data-errormessage-value-missing' => 'This input is required!',
                    'data-style' => 'btn-info btn-outline'),
                'choices' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3
                ),

            ))
            ->add('nbrLimiteParSemaine', ChoiceType::class, array(
                'attr' => array('class' => 'col-md-1 form-control selectpicker m-b-20 m-r-10',
                    'data-errormessage-value-missing' => 'This input is required!',
                    'data-style' => 'btn-info btn-outline'),
                'choices' => array(
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8
                ),

            ))
            ->add('dureeTimeout', ChoiceType::class, array(
                'attr' => array('class' => 'col-md-1 form-control selectpicker m-b-20 m-r-10',
                    'data-errormessage-value-missing' => 'This input is required!',
                    'data-style'=>'btn-info btn-outline'),
                'choices' => array(
                    10 => 10,
                    15 => 15,
                    20 => 20,
                    25 => 25
                ),

            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'IT\ReservationBundle\Entity\Regles'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'it_reservationbundle_regles';
    }


}
