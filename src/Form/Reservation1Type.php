<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Reservation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateReservation')
            ->add('dateDepart', DateType::class, [
                'attr' => ['class' => 'js-datepicker'],    'html5' => false,    'widget' => 'single_text',


            ])
            ->add('dateArrivee')

            ->add('voucher')
            ->add('nbrAdulte')
            ->add('nbrEnfant')
            ->add('facture')
            ->add('client')
            ->add('type')
            ->add('extras')
            ->add('chambre')
            ->add('reduction')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'compound' => true,
            'multiple' => true,
        ]);
    }
}
