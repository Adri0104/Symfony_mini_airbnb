<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateFormType extends ApplicationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword', PasswordType::class, $this->getConfiguration("Ancien mot de passe", "Donnez votre ancien mot de passe"))
        ->add('newPassword', PasswordType::class, $this->getConfiguration("Nouveau mot de passe", "Donnez votre nouveu mot de passe"))
        ->add('confirmPassword', PasswordType::class, $this->getConfiguration("Confirmer votre nouveau mot de passe", "Donnez votre confirmation du nouveu mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
