<?php

namespace App\Controller;
use App\Entity\Test;
use App\Entity\User;
use App\Entity\Cours;
use App\Form\TestType;
use App\Form\Test2Type;
use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        return $this->render('front/sucess.html.twig', [
            'controller_name' => 'FrontController',
            'user' =>  $user = $this->getUser()->getNom(),
            'id' => $this->getUser()->getId(),
            'cours' => $cours

        ]);
    }

    /**
     * @Route("/error_front", name="error_front")
     */
    public function index2(): Response
    {
        return $this->render('front/page_notfound.html.twig', [
            'controller_name' => 'FrontController',
            'id' => $this->getUser()->getId()
        ]);
    }

    /**
     * @Route("/cours_front", name="cours_front")
     */
    public function index3(TestRepository $testRepository): Response
    {
        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        return $this->render('front/cours_front.html.twig', [
            'controller_name' => 'FrontController',
            'cours' => $cours,
            'id' => $this->getUser()->getId()
        ]);
    }

        /**
         * @Route("/{idUtilisateur}/test_front", name="test_front" , methods={"GET","POST"})
         */
        public function index4(TestRepository $testRepository,Request $request, Test $test): Response
    {

        $n = $testRepository->createQueryBuilder('t')
            ->select('count(t.idTest)')
            ->where('t.note = -1 and t.status=0 and t.idUtilisateur='.$this->getUser()->getId().'')
            ->getQuery()
            ->getSingleScalarResult();
        if ($n != 0) {
            $form = $this->createForm(Test2Type::class, $test);
            $form->handleRequest($request);
            $tests = $this->getDoctrine()
                ->getRepository(Test::class)
                ->findBy(['status' => '0','note' => '-1','idUtilisateur'=>$this->getUser()->getId()]);

            if ($form->isSubmitted() && $form->isValid()) {
                $test->setStatus(1);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('front_log');
            }



            return $this->render('front/cours_front.html.twig', [
                'tests' => $tests,
                'test' => $test,
                'id' => $this->getUser()->getId(),
                'user' => $user = $this->getUser()->getNom(),
                'form' => $form->createView()
            ]);
        }
        if ($n == 0 )
        {
            return $this->redirectToRoute('error_front');
        }

    }




}

