<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        //Si il y a moins de 4 mots dans la liste, interdire "Facile", parce que ça ne fait pas assez de réponses aléatoires pour le QCM
        if ($options['nb_trad_liste'] < 4) {
            $builder
                ->add('difficulte', ChoiceType::class, [
                    'choices' => [
                        'Moyen' => 'moyen',
                        'Difficile' => 'difficile',
                        'Facile' => 'facile',
                    ],
                    //Griser le choix facile
                    'choice_attr' => [
                        'Facile' => ['title' => 'Indisponible, car trop peu de mots dans la liste', 'disabled' => 'disabled', 'style' => 'color: #999; font-style: italic;'],
                    ],
                    'label' => 'Difficulté'
                ])
                ->add('langue_cible', HiddenType::class);
        } else {

            $builder
                ->add('difficulte', ChoiceType::class, [
                    'choices' => [
                        'Facile' => 'facile',
                        'Moyen' => 'moyen',
                        'Difficile' => 'difficile',
                    ],
                    'label' => 'Difficulté'
                ])
                ->add('langue_cible', HiddenType::class);;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        //Il faut donner une valeur par défault à nb_trad_liste, sinon l'option n'est pas pris en compte et on obtient une erreur
        $resolver->setDefaults([
            'nb_trad_liste' => 0,
        ]);
    }
}
