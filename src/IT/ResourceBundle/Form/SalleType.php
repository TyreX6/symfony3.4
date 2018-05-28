<?php

namespace IT\ResourceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('numero_salle')
            ->add('status', ChoiceType::class, array(
                'attr' => array(),
                'choices' => array(
                    "Fonctionnel" => 1,
                    "Détruit" => 0,
                ),

            ))
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
            'data_class' => 'IT\ResourceBundle\Entity\Salle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'it_ResourceBundle_salle';
    }


}
