<?php

namespace App\Form;

use App\Entity\Stages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Repository\CoursRepository;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;

class StagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('texte')
            ->add('essai')
            ->add('correction')
            ->add('temps')
            ->add('jeu')
            ->add('imageFile',VichImageType::class,[
                'allow_delete' => true,
                'download_link' => false
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stages::class,
        ]);
    }
}
