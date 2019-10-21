<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationFormType extends AbstractType
{
    protected function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}
