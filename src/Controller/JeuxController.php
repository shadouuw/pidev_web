<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Jeux;
use App\Form\JeuxType;
use App\Repository\JeuxRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use PChess\Chess\Output\AsciiOutput;
use PChess\Chess\Output\UnicodeOutput;
use PGNChess\Game;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use PChess\Chess\Chess;




/**
 * @Route("/jeux")
 */
class JeuxController extends AbstractController
{






    /**
     * @Route("/", name="jeux_index", methods={"GET"})
     */
    public function index(JeuxRepository $jeuxRepository,FlashyNotifier $flashy): Response
    {




        $jeuxes = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findAll();
        $jeuxtriup = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findBy(array(), array('titre'=>'asc'));
        $jeuxtridown = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findBy(array(), array('titre'=>'desc'));;
       /* $jeuxrecherche = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findBy(array('cours' => $jeuxes->getCours()));*/
        //$user = $this->getUser()->getNom()
dump($jeuxRepository->Total_Jeux());
        return $this->render('jeux/index.html.twig', [
            $flashy->success('Event created!', 'http://your-awesome-link.com'),

        'jeuxes' => $jeuxes,
            'user' =>  $this->getUser()->getNom(),
            'totalmg' =>$jeuxRepository->Total_Jeux(),
            'totalcours' =>$jeuxRepository->Total_Cours(),
            //'rechcerchecours'=>$jeuxrecherche,
            'jeuxtriup'=>$jeuxtriup,
                        'jeuxtridown'=>$jeuxtridown

        ]);
    }


    /**
     * @Route("/", name="jeux_shoow", methods={"GET"})
     */
    public function show(): Response
    {
        $jeuxes = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findAll();

        return $this->render('jeux/index.html.twig', [
            'jeuxes' => $jeuxes,
            'user' =>  $this->getUser()->getNom(),

        ]);
    }



    /**
     * @Route("/new", name="jeux_new", methods={"GET","POST"})
     */
    public function new(Request $request,\Swift_Mailer $mailer): Response
    {



        $jeux = new Jeux();
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = (new \Swift_Message('New Mini-Game Added'))
                ->setFrom('sami.belhajhassine@esprit.tn')
                ->setTo($this->getUser()->getEmail())
                ->setBody('New Mini-game "'.$jeux->getTitre().'" related to the course "'.$jeux->getCours().'" is now available')
            ;

            $mailer->send($message);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jeux);
            $entityManager->flush();


            return $this->redirectToRoute('jeux_index');
        }

        return $this->render('jeux/new.html.twig', [
            'jeux' => $jeux,
            'form' => $form->createView(),
            'user' => $this->getUser()->getNom(),
        ]);
    }
/*
    /**
     * @Route("/{id}", name="jeux_show", methods={"GET"})
     */
   /* public function show(Jeux $jeux): Response
    {
        return $this->render('jeux/show.html.twig', [
            'jeux' => $jeux,
            'user' =>  $user = $this->getUser()->getNom()
        ]);
    }*/

    /**
     * @Route("/{id}/edit", name="jeux_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Jeux $jeux): Response
    {
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jeux_index');
        }
        return $this->render('jeux/edit.html.twig', [
            'jeux' => $jeux,
            'form' => $form->createView(),
            'user' =>  $this->getUser()->getNom(),
        ]);
    }

    /**
     * @Route("/{id}", name="jeux_delete", methods={"POST"})
     */
    public function delete(Request $request, Jeux $jeux): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeux->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jeux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('jeux_index');
    }

    /**
     * @Route("/tu", name="jeux_indexu", methods={"GET"})
     */

    public function triup(Request $request,NormalizerInterface $Normalizer,JeuxRepository $jeuxRepository): Response
    {
        /*$jeuxtriup = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findBy(array(), array('titre'=>'asc'));
        dump($jeuxtriup);
        return $this->render('jeux/index.html.twig', [

            'jeuxes'=>$jeuxtriup,
            'user' =>  "admin12",
            'totalmg' =>2,
            'totalcours' =>3,
            //'rechcerchecours'=>$jeuxrecherche,

        ]);

*/
        $requestString=$request->get('titre');
        $jeuxtriup=null;
if($requestString=="upjeux")
{

    $jeuxtriup =$jeuxRepository->findCours();

}
elseif ($requestString=="downjeux")
{
    $jeuxtriup =$jeuxRepository->findCoursd();
}
elseif(strlen($requestString )>= 2){
    $jeuxtriup =$jeuxRepository->findCourst($requestString);
}
else
{
    $jeuxtriup =$jeuxRepository->findCours();

}
dump($requestString);
            dump($jeuxtriup);
            $jsoncontentc =$Normalizer->normalize($jeuxtriup,'json',['groups'=>'post:read']);
              dump($jsoncontentc);
            $jsonc=json_encode($jsoncontentc);
            dump($jsonc);
            if(  $jsonc == "[]" )
            {
                return new Response(null);
            }
            else{        return new Response($jsonc);
            }






    }








    /**
     * @Route("/front", name="jeux_front", methods={"GET"})
     */
    public function front(JeuxRepository $jeuxRepository): Response
    {
        $jeuxes = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findall();
        $jeuxtriup = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findBy(array(), array('titre'=>'asc'));
        $jeuxtridown = $this->getDoctrine()
            ->getRepository(Jeux::class)
            ->findBy(array(), array('titre'=>'desc'));;

         $jeuxes=$jeuxRepository->availablegames();

        /* $jeuxrecherche = $this->getDoctrine()
             ->getRepository(Jeux::class)
             ->findBy(array('cours' => $jeuxes->getCours()));*/
        //$user = $this->getUser()->getNom()
        dump($jeuxRepository->Total_Jeux());
        return $this->render('jeux/jeuxfront.html.twig', [
            'jeuxes' => $jeuxes,
            $user = $this->getUser()->getNom(),
            'totalmg' =>$jeuxRepository->Total_Jeux(),
            'totalcours' =>$jeuxRepository->Total_Cours(),
            //'rechcerchecours'=>$jeuxrecherche,
            'jeuxtriup'=>$jeuxtriup,
            'jeuxtridown'=>$jeuxtridown

        ]);
    }






    /*
        /**
         * @Route("/", name="lista", methods={"GET"})
         */
/*
public function listAction(Request $request)
{
    $em=$this->getDoctrine()->getManager();
    $dql= "SELECT titre from AppBundle:Jeux titre";
    $query=$em->createQuery($dql);
    /**
     * @Var $paginator \Knp\Component\Pager\Paginator
     */
   /* $paginator=$this->get('knp_paginator');
    $result=$paginator->paginate(
        $query,
        $request->query->getInt('page',1),
                $request->query->getInt('limit',5)

    );
    return $this->render('jeux/index.html.twig',[
       'jeuxess'=>$result
    ]);

}*/




    /**
     * @Route("/frontu", name="jeux_rech", methods={"GET"})
     */

    public function rech(Request $request,NormalizerInterface $Normalizer,JeuxRepository $jeuxRepository): Response
    {

        $requestString = $request->get('rech');
        $jeuxtriup = null;
        if (strlen($requestString) >= 2) {
            $jeuxtriup = $jeuxRepository->findCourst($requestString);
        } else {
            $jeuxtriup = $jeuxRepository->availablegames();
        }

        dump($requestString);
        dump($jeuxtriup);
        $jsoncontentc = $Normalizer->normalize($jeuxtriup, 'json', ['groups' => 'post:read']);
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
         * @Route("/cowsnbulls", name="cowsnbulls", methods={"GET","POST"})
         */
        public function cowsnbulls(Request $request): Response
    {

        return $this->render('jeux/cowsnbulls.html.twig');
    }

    /**
     * @Route("/checkers", name="checkers", methods={"GET","POST"})
     */
    public function checkers(Request $request): Response
    {

        return $this->render('jeux/checkers.html.twig');
    }

    /**
     * @Route("/sudoku", name="sudoku", methods={"GET","POST"})
     */
    public function sudo(Request $request): Response
    {

        return $this->render('jeux/sudoku.html.twig');
    }


    /**
     * @Route("/memory", name="memory", methods={"GET","POST"})
     */
    public function memory(Request $request): Response
    {

        return $this->render('jeux/memory.html.twig');
    }


    /**
     * @Route("/menu", name="menu", methods={"GET","POST"})
     */
    public function menu(Request $request): Response
    {

        return $this->render('jeux/menu.html.twig');
    }


    /**
     * @Route("/tic", name="tic", methods={"GET","POST"})
     */
    public function tic(Request $request): Response
    {

        return $this->render('jeux/tictactoe.html.twig');
    }

    /**
     * @Route("/chess", name="chess", methods={"GET","POST"})
     */
    public function generateLinks()
    {
        $chess = new Chess();
        echo (new UnicodeOutput())->render($chess);
        $moves = $chess->moves();
        $move = $moves[random_int(0, count($moves) - 1)];
        $chess->move($move);
        return $this->render('jeux/chess.html.twig');

    }



    public function sendEmail(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody('You should see me from the profiler!')
        ;

        $mailer->send($message);

        // ...
    }


}