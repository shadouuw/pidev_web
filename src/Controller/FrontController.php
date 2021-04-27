<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/front", name="front")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
    /**
     * @Route("/front_log", name="front_log")
     */
    public function sucess(): Response
    {
        return $this->render('front/sucess.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }
}
