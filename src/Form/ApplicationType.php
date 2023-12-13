<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

/**
 * ApplicationType est une classe abstraite qui étend AbstractType.
 * Elle définit une méthode qui est getConfiguration(label + placeholder + tableau(option))
 */
class ApplicationType extends AbstractType
{
    protected function getConfiguration(string $label, string $placeholder, array $options = []): array
    {
        return array_merge_recursive(
            [
                'label' => $label,
                'attr' => [
                    'placeholder' => $placeholder
                ],
            ],
            $options
        );
    }
}
