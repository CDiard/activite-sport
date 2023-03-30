<?php

namespace App\Form;

use App\Entity\Result;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('score', IntegerType::class, [
                'label' => 'Score',
            ])
            ->add('time', DateIntervalType::class, [
                'label' => 'Temps',
                'labels' => [
                    'minutes' => 'Minutes',
                    'seconds' => 'Secondes',
                ],
                'minutes' => range(1, 60),
                'seconds' => range(1, 60),
                'with_days' => false,
                'with_hours' => false,
                'with_months' => false,
                'with_weeks' => false,
                'with_years' => false,
                'with_minutes' => true,
                'with_seconds' => true,
                'widget' => 'integer',
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Result::class,
        ]);
    }
}
