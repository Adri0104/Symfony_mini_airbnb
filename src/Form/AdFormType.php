<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdFormType extends AbstractType
{
    private function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Votre titre de l'annonce"))
            ->add('slug', TextType::class, $this->getConfiguration("Url", "Votre adresse", [
                'required' => false
            ]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("Url de l'image", "Votre url"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Introduction globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Votre description"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambre", "Exemple : 3"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Votre prix"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageFormType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
