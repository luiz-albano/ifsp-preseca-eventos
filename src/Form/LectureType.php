<?php

namespace App\Form;

use App\Entity\Lecture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LectureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lecturer')
            ->add('location')
            ->add('attendees_quantity')
            ->add('subtitle')
            ->add('description')
            ->add('start_date')
            ->add('end_date')
            ->add('lecturer_image')
            ->add('created_at')
            ->add('updated_at')
            ->add('event')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lecture::class,
        ]);
    }
}
