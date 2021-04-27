<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request ,AuthenticationUtils $utils): Response

    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            {
                return $this->redirectToRoute('front_log');
            }
            else {
                return $this->redirectToRoute('jeux_index');
            }
        }


        $error=$utils->getLastAuthenticationError();
        $last_username=$utils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $last_username
        ]);
    }




    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {



    }

}
