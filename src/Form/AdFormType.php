<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'autofocus' => true, 'class' => 'form-control',
                    'placeholder' => "Titre de l'annonce"
                ]
            ])
            ->add('slug')
            ->add('price')
            ->add('introduction', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "L'introduction de l'annonce"
                ]
            ])
            ->add('content')
            ->add('coverImage')
            ->add('rooms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
