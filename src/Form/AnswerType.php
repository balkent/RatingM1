<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Student;
use App\Entity\Exercise;
use App\Entity\Supplement;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => function (Student $student): string {
                    return $student->getDisplay();
                }
            ])
            ->add('exercise', EntityType::class, [
                'class' => Exercise::class,
                'choice_label' => 'title'
            ])
            ->add('result', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'style' => "height: 100px"
                ],
            ])
            ->add('rating', NumberType::class, [
                'required' => false,
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
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
