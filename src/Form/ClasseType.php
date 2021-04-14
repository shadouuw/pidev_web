<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Cours;
use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClasseType extends AbstractType
{
    private $userRepository;
    public  function __construct(UserRepository $userRepository)
    {
    $this->userRepository=$userRepository;

    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $user=new User();

        $builder
            ->add('nom_class')
            ->add('age')


            ->add('id_utilisateur',ChoiceType::class,[

                    'multiple' => false,
                'choices' =>

    $this->userRepository->createQueryBuilder('u')->select('u.id  as id')->where('u.role=2')->getQuery()->getResult(),
                'choice_label' => function ($choice) {
                return $choice;
            },]


            );



    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
