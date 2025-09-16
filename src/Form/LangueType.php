<?php

namespace App\Form;

use App\Entity\Langue;
use App\Entity\ListeVocabulaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\DataTransformerInterface;

class LangueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('majImportante', CheckboxType::class, [
                'label' => 'Les majuscules sont importantes (exemple en allemand)',
                'required' => false,
            ])
            ->add('caracteresSpeciaux', TextAreaType::class, ['attr' => [
                'placeholder' => 'à é è ê ë ô ù û ç',
                'rows' => 3,
            ]])

            ->add('listesVocabulaire', EntityType::class, [
                'class' => ListeVocabulaire::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'required' => false,
            ])
        ;

        $builder->get('caracteresSpeciaux')->addModelTransformer(
            new class implements DataTransformerInterface {
                public function transform(mixed $value): string
                {
                    if(!$value){
                        return '';
                    }
                    return implode(' ',$value); 
                }

                public function reverseTransform($value):array{
                    if(!$value){
                        return [];
                    }
                    $chars = preg_split('/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
                    return array_values(array_unique($chars));
                }


            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Langue::class,
        ]);
    }
}
