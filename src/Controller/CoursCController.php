<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\User;
use App\Form\Cours1Type;
use App\Repository\CoursRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/cours/c")
 */
class CoursCController extends AbstractController
{
    /**
     * @Route("/", name="cours_c_index", methods={"GET"})
     */
    public function index(CoursRepository $coursRepository,UserRepository $userRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('front');
        }

        else {
            $tot=$coursRepository->createQueryBuilder('c')->select('count(c)')->getQuery()->getSingleScalarResult();
            $calcm=$coursRepository->createQueryBuilder('c')->select('count(c)')->where("c.domaine = 'Math'")->getQuery()->getSingleScalarResult();
            $calcf=$coursRepository->createQueryBuilder('c')->select('count(c)')->where("c.domaine = 'French'")->getQuery()->getSingleScalarResult();
            $calce=$coursRepository->createQueryBuilder('c')->select('count(c)')->where("c.domaine = 'Science'")->getQuery()->getSingleScalarResult();
            $calcf=($calcf/$tot)*100;
            $calcm=($calcm/$tot)*100;
            $calce=($calce/$tot)*100;



            return $this->render('cours_c/index.html.twig', [
                'cours' => $coursRepository->findAll(),
                'user' =>  $user = $this->getUser()->getNom(),
                'st1'=>$calcm,
                'user_nmbr' => $userRepository->createQueryBuilder('u')->select('count(u)')->getQuery()->getSingleScalarResult(),
                'course_nmbr'=>$coursRepository->createQueryBuilder('c')->select('count(c)')->getQuery()->getSingleScalarResult(),
                'st2'=>$calcf,
                'st3'=>$calce,
                'img' => $this->getUser()->getImg()
            ]);

        }



    }



    /**
     * @Route("/new", name="cours_c_new", methods={"GET","POST"})
     */
    public function new(Request $request , ValidatorInterface $validator): Response
    {
        $cour = new Cours();
        $form = $this->createForm(Cours1Type::class, $cour);
        $form->handleRequest($request);
        $errors = $validator->validate($cour);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();



            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('link_cours');
        }

        return $this->render('cours_c/new.html.twig', [
            'cour' => $cour,
            'img' => $this->getUser()->getImg(),
            'user' => $this->getUser()->getNom(),
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }



    /**
     * @Route("/{id}/edit", name="cours_c_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cours $cour , ValidatorInterface $validator): Response
    {
        $form = $this->createForm(Cours1Type::class, $cour);
        $form->handleRequest($request);
        $errors = $validator->validate($cour);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_cours');
        }

        return $this->render('cours_c/edit.html.twig', [
            'cour' => $cour,
            'img' => $this->getUser()->getImg(),
            'user' => $this->getUser()->getNom(),
            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/{id}", name="cours_c_delete", methods={"POST"})
     */
    public function delete(Request $request, Cours $cour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getIdCours(), $request->request->get('_token'))) {
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
            'img' => $this->getUser()->getImg(),
            'user' =>  $user = $this->getUser()->getNom()

        ]);
    }



}
