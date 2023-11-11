<?php

namespace App\Form;

use App\Entity\Supplement;
use App\Entity\SupplementType as SupplementTypeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('rating')
            ->add('type', EntityType::class, [
                'class' => SupplementTypeEntity::class,
                'choice_label' => function (SupplementTypeEntity $Supplement): string {
                    return $Supplement->getDisplay();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supplement::class,
        ]);
    }
}
