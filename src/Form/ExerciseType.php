<?php

namespace App\Form;

use App\Entity\Subject;
use App\Entity\Exercise;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'libelle',
            ])
            ->add('title')
            ->add('subTitle', CKEditorType::class, [
                'config' => array(
                    'uiColor' => '#ffffff',
                    //...
                ),
                'attr' => [
                    'class' => 'form-control',
                    'style' => "height: 100px"
                ],
                'required' => false,
            ])
            ->add('maximumRating', NumberType::class, [
                'required' => false,
            ])
            ->add('picture', FileType::class, [
                'label' => 'Image de l\'excercise',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PNG ou JPG',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
        ]);
    }
}
