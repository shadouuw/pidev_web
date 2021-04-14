<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use  App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class Userfixture extends Fixture
{

    private $encoder;
    public  function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
       $user=new User();

         $user->setNomUtilisateur('admin3');
$password=$this->encoder->encodePassword($user,'123456');
         $user->setMotDePasse($password);


   $manager->persist($user);

        $manager->flush();
    }
}
