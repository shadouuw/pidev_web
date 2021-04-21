<?php

namespace App\Controller;

use App\Controller\CoursCController;

use App\Entity\User;

use App\Repository\CoursRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use  Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends AbstractController
{
    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client = null;

    /**
     *
     */
    public function setUp()
    {
        $this->client->insulate();
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {


    }


    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $utils,CoursRepository $coursRepository): Response

    {



        $session = new Session();



        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('front_log');
            } else if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('cours_c_index');
            }
            else{


echo "<script> alert('block'); </script> ";
                return $this->redirectToRoute('logout') ;




            }
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $error = $utils->getLastAuthenticationError();
        $last_username = $utils->getLastUsername();




        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $last_username,
            'user' => $user,

            'password' => $password=null


        ]);
    }




    /**
     * @Route("/login2", name="login2")
     */
    public function login2(Request $request,AuthenticationUtils $utils, UserRepository $userRepository): Response

    {
        $password=null;


        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('front_log');
            } else {
                return $this->redirectToRoute('cours_c_index');
            }
        }
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $error = $utils->getLastAuthenticationError();
        $last_username = $utils->getLastUsername();






        $word=$_GET['demo'];
        $qb = $userRepository->createQueryBuilder('u');
       $last_username= $qb->select('u.nomUtilisateur')->where(
            $qb->expr()->like('u.img', ':user')
        )
            ->setParameter('user','%'.$word.'%')
            ->getQuery()
            ->getSingleScalarResult();
        $password=$qb->select('u.token')->where(
            $qb->expr()->like('u.img', ':user')
        )
            ->setParameter('user','%'.$word.'%')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'password' => $password,
            'last_username' => $last_username,
            'user' => $user
        ]);


    }
}