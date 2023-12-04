<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Même raisonnement que pour ApplicationType
 */
class ImageType extends AbstractType
{
    /**
     * Ici on donne un attribut suppplémentaire qui est "placeholder" et qui permet d'avoir du texte pré-rempli dans les champs à compléter par l'utilisateur.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'attr' => [
                    'placeholder' => 'Url de l\'image'
                ]
            ])
            ->add('caption', TextType::class, [
                'attr' => [
                    'placeholder' => 'description de l\'image'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
