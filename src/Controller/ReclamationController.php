<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(): Response
    {
        $reclamations = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()



        ]);
    }

    /**
     * @Route("/new", name="reclamation_new", methods={"GET","POST"})
     */
    public function new(Request $request , ValidatorInterface $validator): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $errors = $validator->validate($reclamation );

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat('Non traité');
            $reclamation->setIdUtilisateur($this->getUser()->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('front_log');
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'id' => $this->getUser()->getId(),

            'form' => $form->createView(),
            'errors' => $errors
        ]);
    }



    /**
     * @Route("/{idReclamation}/edit", name="reclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'user' =>  $user = $this->getUser()->getNom(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idReclamation}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdReclamation(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index');
    }
    /**
     * @Route("/{id}", name="reclamation_detail", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/detail.html.twig', [
            'reclamation' => $reclamation,
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' => $user = $this->getUser()->getNom()
        ]);
    }
    /**
     * @Route("/send", name="reclamation_send", methods={"GET","POST"})
     */
    public function send(\Swift_Mailer $mailer,Request $request): Response
    {
        //récupérer le mail de réception, le contenu de msg ainsi l id de réclamation
        $msg = $request->get('msg');
        $mailuser = $request->get('mailuser');
        $idreclamation = $request->get('idr');

        //préparer le msg
        $message = (new \Swift_Message('Réponse reclamation'))
            ->setFrom('1magicbook1@gmail.com')
            ->setTo($mailuser)
            ->setBody(
                $msg
            )
        ;

        //envoyer le msg
        $mailer->send($message);


        //update état
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($idreclamation);
        $reclamation->setEtat("Traité");
        $em->flush();

        //retour vers la page index
        return $this->redirectToRoute('reclamation_index');
    }


}
