<?php

namespace App\Form;

use App\Entity\Challenge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChallengeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('ordre', ChoiceType::class, [
                'choices'  => [
                    'Ascendant' => 0,
                    'Descendant' => 1,
                ],
                'label' => 'Notation de l\'épreuve',
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Score' => 'score',
                    'Temps' => 'temps',
                ],
                'label' => 'Type d\'épreuve',
            ])
            ->add('coefficient', IntegerType::class, [
                'label' => 'Coefficient de l\'épreuve',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Challenge::class,
        ]);
    }
}
