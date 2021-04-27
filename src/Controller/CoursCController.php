<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\User;
use App\Form\Cours1Type;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cours/c")
 */
class CoursCController extends AbstractController
{
    /**
     * @Route("/", name="cours_c_index", methods={"GET"})
     */
    public function index(CoursRepository $coursRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('front');
        }



        else {
            return $this->render('cours_c/index.html.twig', [
                'cours' => $coursRepository->findAll(),
                'user' =>  $user = $this->getUser()->getNom()
            ]);
        }



    }



    /**
     * @Route("/new", name="cours_c_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cour = new Cours();
        $form = $this->createForm(Cours1Type::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();



            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('link_cours');
        }

        return $this->render('cours_c/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/edit", name="cours_c_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cours $cour): Response
    {
        $form = $this->createForm(Cours1Type::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_cours');
        }

        return $this->render('cours_c/edit.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cours_c_delete", methods={"POST"})
     */
    public function delete(Request $request, Cours $cour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId_cours(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_cours');
    }

    /**
         * @Route("/link_cours" ,name="link_cours", methods={"GET"})
     */
    public function index1(CoursRepository $coursRepository): Response
    {
        return $this->render('cours_c/course.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }



}
