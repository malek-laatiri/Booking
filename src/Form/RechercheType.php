<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateReservation')
            ->add('dateArrivee')
            ->add('dateDepart')
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
        ]);
    }
}
