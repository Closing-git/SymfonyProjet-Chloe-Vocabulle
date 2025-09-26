<?php

namespace App\Form;

use App\Entity\Langue;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class SearchFiltersListesVocabulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('langue', EntityType::class, [
                'required' => false,
                'class' => Langue::class,
                'placeholder' => 'Toutes les langues',
                'label' => 'Choix langue',
                'choice_label' => "nom",
            ])
            ->add('ordre', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Choisir le type de tri...',
                'label' => 'Tri',
                'choices' => [
                    'A -> Z' => 'alpha',
                    'Z -> A' => 'antiAlpha',
                    'Du plus ancien au plus récent' => 'olderFirst',
                    'Du plus récent au plus ancien' => 'newerFirst',
                    'Mieux notés' => 'bestNoteFirst',
                    'Meilleur score en premier' => 'bestScoreFirst',
                    'Moins bon score en premier' => 'worseScoreFirst',
                ]
            ])
            ->add('statut', ChoiceType::class, [
                'required' => false,
                'label' => 'Public/Privé',
                'placeholder' => 'Tous',
                'choices' => [
                    'Privé' => false,
                    'Public' => true,
                ]
            ])
            ->add('ownCreator', CheckboxType::class, [
                'required' => false,
                'label' => 'Créé par moi ?',
            ])
            ->add('fav', CheckboxType::class, [
                'required' => false,
                'label' => 'Montrer uniquement les favoris',
            ])
            ->add('titre', TypeTextType::class, [
                'required' => false,
                'label' => 'Rechercher par titre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
