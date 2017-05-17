<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, array(
            'label' => 'Prenom',
        ))
        ->add('lastname', TextType::class, array(
            'label' => 'Nom',
        ))
        ->add('country', CountryType::class, array(
            'label' => 'Votre pays',
        ))
        ->add('birthdate', BirthdayType::class, array(
            'format' => 'dd MM yyyy',
            'label' => 'date de naissance',
        ))
        ->add('reduce', CheckboxType::class, array(
            'label' => 'Tarif réduit',
            'required' => false,
        ))
        ->add('submit_add', SubmitType::class, array(
            'label' => 'Ajouter un billet',
    ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket',
            'validation_groups' => array('ticket'),
        ));
    }


    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
        return 'form_ticket';
    }
}
