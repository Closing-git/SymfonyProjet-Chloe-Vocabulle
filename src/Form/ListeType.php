<?php

namespace App\Form;

use App\Entity\Langue;
use App\Entity\Utilisateur;
use App\Entity\ListeVocabulaire;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ListeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('publicStatut')

            //Mapped => False / Création de Type sans qu'ils soient liés à la DB
            //Il faut incruster les données pour create ou pour update via le controller
            ->add('langue1', EntityType::class, [
                'class' => Langue::class,
                'choice_label' => 'nom',
                'label' => 'Langue 1',
                'mapped' => false,
                'required' => true,
            ])
            ->add('langue2', EntityType::class, [
                'class' => Langue::class,
                'choice_label' => 'nom',
                'label' => 'Langue 2',
                'mapped' => false,
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListeVocabulaire::class,
        ]);
    }
}
