<?php

namespace App\Form;

use App\Entity\Code;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hash')
            ->add('url')
            ->add('created_at')
            ->add('updated_at')
            ->add('used_by')
            ->add('lecture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Code::class,
        ]);
    }
}
