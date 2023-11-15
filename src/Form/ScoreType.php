<?php

namespace App\Form;

use App\Entity\Score;
use App\Entity\Student;
use App\Entity\Subject;
use App\Form\StudentType;
use App\Form\SubjectType;
use App\Entity\Supplement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating')
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => function($choice, $key, $value) {            
                    return $choice->getDisplay();
                },
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => function($choice, $key, $value) {            
                    return $choice->getLibelle().' ('.$choice->getMaximumRating().')';
                },
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('supplements', EntityType::class, [
                'class' => Supplement::class,
                'expanded' => false,
                'multiple' => true,
                'group_by' => function($choice, $key, $value) {            
                    return $choice->getType()->getLibelle();
                },
                'attr' => [
                    'style' => 'height: 1050px;'
                ]
            ])
            // ->add('supplements', CollectionType::class, [
            //     'entry_type' => EntityType::class,
            //     'entry_options' => [
            //         'label' => false,
            //         'class' => Supplement::class,
            //     ],
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Score::class,
        ]);
    }
}
