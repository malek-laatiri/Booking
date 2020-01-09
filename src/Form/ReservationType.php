<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateArrivee', DateType::class, [
                'widget' => 'single_text',
                'data'=>new \DateTime(),

                'attr' => ['class' => 'js-datepicker'],])
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'data'=>new \DateTime(),
                'attr' => ['class' => 'js-datepicker'],])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var  $reservation */
                $reservation = $form->getData();
                /** @var  $arrivee */
                $arrivee = $reservation->getdateArrivee();
                /** @var  $depart */
                $depart = $reservation->getdateDepart();

                $diff = ($depart->diff($arrivee)->format("%a"));
                dump((int)$diff);
                dump($reservation->getType()->getPrix());
                $facture = new Facture();
                $facture->setMontant((int)$diff * $reservation->getType()->getPrix() * $reservation->getnbrAdulte());
                $reservation->setFacture($facture);
            })
            ->add('nbrAdulte',IntegerType::class,array(
                'attr' => array('min' => 1, 'max' => 3)
            ))
            ->add('nbrEnfant',IntegerType::class,array(
        'attr' => array('min' => 1, 'max' => 3)
    ))
            ->add('client', CollectionType::class, [
                'entry_type' => ClientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => false])
            ->add('type')
            ->add('extras', null, [
                'attr' => ['class' => 'my-select ']])
            ->add('reduction', CollectionType::class, [
                'entry_type' => ReductionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
                'by_reference' => false,
                'label' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            "allow_extra_fields" => true,


        ]);
    }
}
