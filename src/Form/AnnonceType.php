<?php

namespace App\Form;

use App\Entity\Voiture;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * "AnnonceType" étend "ApplicationType" et donc utilise la méthode "getConfiguration"
 */
class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, $this->getConfiguration("Marque de la voiture", "Veuillez donner la marque de votre voiture"))
            ->add('modele', TextType::class, $this->getConfiguration("Modele de la voiture", "Veuillez donnez la modèle de votre voiture"))
            ->add('cover', TextType::class, $this->getConfiguration("Url de l'image", "Veuillez donner le nom de votre voiture"))
            ->add('km', IntegerType::class, $this->getConfiguration("Nombre de kilomètres de la voiture", "Veuillez donner le nombre de km de la voiture"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix de la voiture", "Veuillez donner le prix de la voiture"))
            ->add('nbProprio', IntegerType::class, $this->getConfiguration("Nombre de propriétaires qui ont possédé cette voiture", "Veuillez donner le nombre de propriétaire"))
            ->add(
                'cylindree',
                IntegerType::class,
                $this->getConfiguration("Cylindrée de la voiture", "Cylindrée de la voiture")
            )
            ->add('puissance', IntegerType::class, $this->getConfiguration("Puissance de la voiture", "Veuillez donner la puissance de votre voiture"))
            ->add('carburant', textType::class, $this->getConfiguration("Carburant de la voiture", "Veuillez donner le carburant de la voiture"))
            ->add('date')
            ->add('transmission', TextType::class, $this->getConfiguration("Transmission de la voiture", "Veuillez donner la transmission de la voiture"))
            ->add('description', textType::class, $this->getConfiguration("Description de la voiture", "Veuillez donner une description de la voiture"))
            ->add('texte', TextareaType::class, $this->getConfiguration("Texte(Optionnel)", "Texte supplémentaire sur la voiture si vous le désirez"))

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
