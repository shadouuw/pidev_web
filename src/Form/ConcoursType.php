<?php

namespace App\Form;

use App\Entity\Concours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomConcours')
            ->add('prix')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('img')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Concours::class,
        ]);
    }
}
