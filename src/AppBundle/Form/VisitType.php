<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitType extends AbstractType
{
    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('visit_date', DateType::class, array(
                'widget' => 'single_text',
                'label' => 'Choisir une date',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'required' => false
            ))
            ->add('ticket_type', ChoiceType::class, array(
                'choices'  => array(
                    'Journée' => 'journee',
                    'Demi-journée' => 'demi-journee'
                ),
                'choice_attr' => array(
                    'Journée' => ['class' => 'form_full_day'],
                    'Demi-journée' => ['class' => 'form_half_day'],
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Réserver'
            ));
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Command',
            'validation_groups' => array('visit'),
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'appbundle_command';
    }


}