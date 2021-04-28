<?php

namespace App\Controller;

use App\Entity\Scores;
use App\Form\ScoresType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/scores")
 */
class ScoresController extends AbstractController
{
    /**
     * @Route("/{id}", name="scores_index", methods={"GET"})
     */
    public function index(int $id): Response
    {
        $scores = $this->getDoctrine()
            ->getRepository(Scores::class)
            ->findBy(
                array('idJeux'=> $id),
                array('score' => 'DESC')
            );


dump($scores);
        return $this->render('scores/index.html.twig', [
            'scores' => $scores,
        ]);
    }

    /**
     * @Route("/new", name="scores_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $score = new Scores();
        $form = $this->createForm(ScoresType::class, $score);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($score);
            $entityManager->flush();

            return $this->redirectToRoute('scores_index');
        }

        return $this->render('scores/new.html.twig', [
            'score' => $score,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="scores_show", methods={"GET"})
     */
    public function show(Scores $score): Response
    {
        return $this->render('scores/show.html.twig', [
            'score' => $score,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="scores_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Scores $score): Response
    {
        $form = $this->createForm(ScoresType::class, $score);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('scores_index');
        }

        return $this->render('scores/edit.html.twig', [
            'score' => $score,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="scores_delete", methods={"POST"})
     */
    public function delete(Request $request, Scores $score): Response
    {
        if ($this->isCsrfTokenValid('delete'.$score->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($score);
            $entityManager->flush();
        }

        return $this->redirectToRoute('scores_index');
    }
}
