<?php

namespace App\Controller;

use App\Entity\Scores;
use App\Entity\Stages;
use App\Form\StagesType;
use App\Repository\JeuxRepository;
use App\Repository\StagesRepository;
use CURLFile;
use Facebook\FacebookApp;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Facebook\FacebookRequestException;

/**
 * @Route("/stages")
 */
class StagesController extends AbstractController
{

    /**
     * @Route("/", name="stages_index", methods={"GET"})
     */
    public function index(StagesRepository $stagesRepository): Response
    {


        $stages = $this->getDoctrine()
            ->getRepository(Stages::class)
            ->findAll();

        return $this->render('stages/index.html.twig', [
            'stages' => $stages,
            'user' => $this->getUser()->getNom(),
            'totalstages' => $stagesRepository->Total_Stages(),
            // 'totalcours' =>$stagesRepository->Total_Cours()
        ]);
    }

    /**
     * @Route("/new", name="stages_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stage = new Stages();
        $form = $this->createForm(StagesType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stage);
            $entityManager->flush();

            return $this->redirectToRoute('stages_index');
        }

        return $this->render('stages/new.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
            'user' => $this->getUser()->getNom(),
        ]);
    }

    /*  /**
       * @Route("/{id}", name="stages_show", methods={"GET"})
       */
    /* public function show(Stages $stage): Response
     {
         return $this->render('stages/show.html.twig', [
             'stage' => $stage,
         ]);
     }
     */

    /**
     * @Route("/{id}/edit", name="stages_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stages $stage): Response
    {
        $form = $this->createForm(StagesType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stages_index');
        }

        return $this->render('stages/edit.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
            'user' => $this->getUser()->getNom(),
        ]);
    }

    /**
     * @Route("/{id}", name="stages_delete", methods={"POST"})
     */
    public function delete(Request $request, Stages $stage): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stages_index');
    }


    /**
     * @Route("/tu", name="stages_indexu", methods={"GET"})
     */
    public function triup(Request $request, NormalizerInterface $Normalizer, StagesRepository $stagesRepository): Response
    {

        $requestString = $request->get('jeu');
        $stagestriup = null;
        if ($requestString == "upstage") {
            $stagestriup = $stagesRepository->findjeu();
        } elseif ($requestString == "downstage") {
            $stagestriup = $stagesRepository->findjeud();
        } else {
            $stagestriup = $stagesRepository->findjeut($requestString);

        }
        dump($requestString);
        dump($stagestriup);
        $jsoncontentc = $Normalizer->normalize($stagestriup, 'json', ['groups' => 'post:read']);
        dump($jsoncontentc);
        $jsonc = json_encode($jsoncontentc);
        dump($jsonc);
        if ($jsonc == "[]") {
            return new Response(null);
        } else {
            return new Response($jsonc);
        }


    }


    /**
     * @Route("/{id}/{numero}/front", name="stages_front", methods={"GET"})
     */
    public function front(StagesRepository $stagesRepository, int $id): Response
    {
        $session = new Session();
        $session->set('scoreg', 0);
        $stages = $this->getDoctrine()
            ->getRepository(Stages::class)
            ->findAll();

        $fs = $stagesRepository->firststage($id);
        $nfs = $stagesRepository->firststagenum($id);
        $ns = $stagesRepository->nextstage($id, $nfs);

        dump($fs);
        dump($nfs);
        dump($ns);

        return $this->render('stages/front.html.twig', [
            'stages' => $stages,
            'fs' => $fs,
            'user' => $this->getUser()->getNom(),
            'ns' => $ns
            // 'totalcours' =>$stagesRepository->Total_Cours()
        ]);
    }


    /**
     * @Route("/{id}/{numero}/front2", name="stages_next", methods={"GET"})
     */
    public function next(StagesRepository $stagesRepository, int $id, int $numero,\Swift_Mailer $mailer): Response
    {
        $stages = $this->getDoctrine()
            ->getRepository(Stages::class)
            ->findAll();

        $fs = $stagesRepository->firststage($id);
        $mfs = $stagesRepository->firststagenum($id);
        $totaltemps = $stagesRepository->totaltemps($id);
      $max=$stagesRepository->max($id);


        $t = $_GET['scorex'];

        $session = new Session();


// set and get session attributes
        $session->set('scoreg', $session->get('scoreg') + $t);

        $ns = $stagesRepository->nextstage($id, $numero);
        dump($totaltemps);
        dump($t);
        dump($session->get('scoreg'));
        dump($fs);
        dump($ns);
        dump($numero);
        dump($mfs);
        dump($numero < $mfs);
        if ($numero <= $mfs) {


            return $this->render('stages/front.html.twig', [
                'stages' => $stages,
                //'fs'=>$fs,
                'user' => $this->getUser()->getNom(),
                'fs' => $ns,
                '$mfs' => $mfs,
                'totaltemps' => $totaltemps
                // 'totalcours' =>$stagesRepository->Total_Cours()
            ]);

        } else {

            $this->addscore($id);
           if($session->get('scoreg')>=$max){
            $message = (new \Swift_Message('New Mini-Game Added'))
                ->setFrom('sami.belhajhassine@esprit.tn')
                ->setTo($this->getUser()->getEmail())
                ->setBody('you have reached max score with '.$session->get('scoreg'))
            ;

            $mailer->send($message);}
            dump($max);
            dump($this->getUser()->getEmail());
            return $this->render('stages/pagescore.html.twig', [
                'stages' => $stages,
                //'fs'=>$fs,
                'user' => $this->getUser()->getNom(),
                'fs' => $fs,
                '$mfs' => $mfs,
                //   'score'=>5000,
                'totaltemps' => $totaltemps

                // 'totalcours' =>$stagesRepository->Total_Cours()
            ]);
        }
    }

















    /*
        /**
         * @Route("/leaderboard", name="leaderboard", methods={"GET","POST"})
         */
    /*  public function lb(StagesRepository $scoreRepository): Response
      {
          $scores= $scoreRepository->findAll();
          return $this->render('scores/index.html.twig',[
          'scores' => $scores,
              'user'=>$this->getUser()->getNom(),

          ]);
      }*/


    public function addscore($jeux)
    {
        $session = new Session();

       $product = new Scores();
        $product->setIdUser($this->getUser()->getNom());
        $product->setIdJeux($jeux);
        $product->setScore($session->get('scoreg'));
       //zyeda
        if($session->get('scoreg')!=0)  {
            $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
        }
        return new Response('Saved new product with id ' . $product->getId());

    }

}


