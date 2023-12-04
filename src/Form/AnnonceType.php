<?php

namespace App\Form;

use App\Entity\Voiture;
use App\Form\ApplicationType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, $this->getConfiguration("Marque", "Donnez la marque de votre voiture"))
            ->add('modele', TextType::class, $this->getConfiguration("Modele", "Donnez la modèle de votre voiture"))
            ->add('cover', TextType::class, $this->getConfiguration("Url de l'image", "Donnez le nom de votre voiture"))
            ->add('km', IntegerType::class, $this->getConfiguration("Km", "Nombre de km de la voiture"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix", "Prix de la voiture"))
            ->add('nbProprio',)
            ->add(
                'cylindree',
                IntegerType::class,
                $this->getConfiguration("Cylindrée", "Cylindrée de la voiture")
            )
            ->add('puissance', IntegerType::class, $this->getConfiguration("Puissance", "Veuillez donner la puissance de votre voiture"))
            ->add('carburant', textType::class, $this->getConfiguration("Carburant", "Veuillez donner le carburant de la voiture"))
            ->add('date')
            ->add('transmission', TextType::class, $this->getConfiguration("Transmission", "Donnez la transmission de la voiture"))
            ->add('description', textType::class, $this->getConfiguration("Description", "Veuillez donner une description de la voiture"))
            ->add('texte', TextareaType::class, $this->getConfiguration("Texte", "Texte supplémentaire sur la voiture"))

            //Insertion ici, de la partie image dans le formulaire
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true, // Permet d'ajouter une image
                'allow_delete' => true // Permet de supprimer une image
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
