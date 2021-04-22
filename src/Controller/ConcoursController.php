<?php

namespace App\Controller;

use App\Entity\Concours;
use App\Entity\Notification;
use App\Form\ConcoursType;
use App\Repository\BlogRepository;
use App\Repository\ConcoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/concours")
 */
class ConcoursController extends AbstractController
{
    /**
     * @Route("/", name="concours_index", methods={"GET"})
     */
    public function index(): Response
    {
        $concours = $this->getDoctrine()
            ->getRepository(Concours::class)
            ->findAll();

        return $this->render('concours/index.html.twig', [
            'concours' => $concours,
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()
        ]);
    }

    /**
     * @Route("/new", name="concours_new", methods={"GET","POST"})
     */
    public function new(Request $request , ValidatorInterface $validator): Response
    {
        $concour = new Concours();
        $errors=null;
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $errors= $validator->validate($concour);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($concour);
            $entityManager->flush();

            return $this->redirectToRoute('concours_index');
        }

        return $this->render('concours/new.html.twig', [
            'concour' => $concour,
            'form' => $form->createView(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom(),
            'errors' => $errors
        ]);
    }


    /**
     * @Route("/{idConcours}/edit", name="concours_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Concours $concour, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ConcoursType::class, $concour);
        $form->handleRequest($request);
$errors=null;
        if($form->isSubmitted())
        {
            $errors= $validator->validate($concour);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('concours_index');
        }

        return $this->render('concours/edit.html.twig', [
            'concour' => $concour,
            'form' => $form->createView(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/{idConcours}", name="concours_delete", methods={"POST"})
     */
    public function delete(Request $request, Concours $concour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$concour->getIdConcours(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($concour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('concours_index');
    }


    /**
     * @Route("/tri_concours" ,name="tri_concours", methods={"GET"})
     */
    public function index2(ConcoursRepository $concoursRepository): Response
    {
        return $this->render('concours/index.html.twig', [
            'concours' => $concoursRepository->createQueryBuilder('u')->select('u')->orderBy('u.dateDebut' ,'asc ')->getQuery()->getResult(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()

        ]);
    }
    /**
     * @Route("/search_concours", name="search_concours")
     */
    public function search(Request $request,ConcoursRepository $concoursRepository): Response
    {
        $nom = $_GET['nom'];
        return $this->render('concours/index.html.twig', [
            'concours' => $concoursRepository->createQueryBuilder('u')->select('u')->where("u.nomConcours   = '".$nom."' ")->getQuery()->getResult(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()

        ]);
    }
}
