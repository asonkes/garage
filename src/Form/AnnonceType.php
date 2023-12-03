<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('modele')
            ->add('cover')
            ->add('slug')
            ->add('km')
            ->add('price')
            ->add('nbProprio')
            ->add('cylindree')
            ->add('puissance')
            ->add('carburant')
            ->add('date')
            ->add('transmission')
            ->add('description')
            ->add('texte');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
