<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReponseMoyenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse', TextType::class, [
                'label' => 'Traduction :',
                'attr' => [
                    'autocomplete' => 'off',
                    //Si la première lettre est vide, on met '' en placeholder
                    'placeholder' => $options['premiere_lettre'] ?? ''
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Options par défaut
            'premiere_lettre' => '',
        ]);
    }
}
