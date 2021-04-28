<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Jeux;
use App\Repository\CoursRepository;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class JeuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('topscore',NumberType::class)
            ->add('cours')
            ->add('diff')

            ->add('imageFile',VichImageType::class,[
                'allow_delete' => true,
                'download_link' => false
            ] )

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Jeux::class,
        ]);
    }
}
