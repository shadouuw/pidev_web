<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\Test;
use App\Entity\User;
use App\Form\TestType;
use App\Form\Test3Type;
use App\Repository\TestRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/test")
 */
class TestController extends AbstractController
{
    /**
     * @Route("/", name="test_index", methods={"GET"})
     */
    public function index(): Response
    {
        $tests = $this->getDoctrine()
            ->getRepository(Test::class)
            ->findAll();

        return $this->render('test/index.html.twig', [
            'tests' => $tests,
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'img' => $this->getUser()->getImg(),
            'user' => $user = $this->getUser()->getNom()
        ]);
    }

    /**
     * @Route("/new", name="test_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $testRepository ,TestRepository $testRepository2): Response
    {
        $test = new Test();
        $user = new User();
        $n = 0;
        $error=null;
        $n1 = 0;

        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if($testRepository2->createQueryBuilder('t')->select('count(t)')->where('t.status=0')->getQuery()->getSingleScalarResult()!=0) {

            if($form->isSubmitted())
            {
                $error='Cannot add a new test because some student didnt pass ';
            }
        }



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $n = $testRepository->createQueryBuilder('u')
                ->select('count(u)')
                ->where('u.role = 0')
                ->getQuery()
                ->getSingleScalarResult();

            $t = $testRepository->findBy(['role' => '0']);
            if($testRepository2->createQueryBuilder('t')->select('count(t)')->where('t.status=0')->getQuery()->getSingleScalarResult()==0) {

                for ($i = 0; $i < $n; $i++) {
                    $n1++;

                    $test->setIdUtilisateur($t[$i]->getId());

                    $test->setNote(-1);
                    $test->setStatus(0);
                    $applicationObj = clone $test;
                    $entityManager->persist($applicationObj);
                    $entityManager->flush();
                    $this->redirect('test_index');
                }
            }
            else
                {
                 $error='Cannot add a new test because some student didnt pass ';

                }

            }





        return $this->render('test/new.html.twig', [
            'test' => $test,
            'form' => $form->createView(),
            'nombr' => $n1,
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'errors'=> $error,

            'user' => $this->getUser()->getNom()
        ]);
    }



    /**
     * @Route("/{idTest}/edit", name="test_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Test $test): Response
    {
        $errors=null;
        $form = $this->createForm(TestType::class, $test);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('test_index');
        }

        return $this->render('test/edit.html.twig', [
            'test' => $test,
            'errors'=> $errors  ,
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' => $this->getUser()->getNom(),
            'img' => $this->getUser()->getImg(),
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{idTest}/correct", name="test_correct", methods={"GET","POST"})
     */
    public function correct(Request $request, Test $test,TestRepository  $testRepository): Response
    {
        $errors=null;
        $t = $testRepository->createQueryBuilder('t')->select('t')->where("t.idTest = " . $test->getIdTest() . " ")->getQuery()->getSingleResult();
        $form = $this->createForm(Test3Type::class, $test);
        $form->handleRequest($request);

        if($test ->getStatus()!=1)
        {
            return $this->redirectToRoute('test_index');
        }

        if ($form->isSubmitted() && $form->isValid()) {
 $test->setStatus(2);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('test_index');
        }


        if($testRepository->createQueryBuilder('t')->select('count(t)')->where("t.idTest = ".$test->getIdTest()." " )->andWhere('t.note = -1')->andWhere('t.status = 1')->getQuery()->getSingleResult() != 0 ) {
            $t = $testRepository->createQueryBuilder('t')->select('t')->where("t.idTest = " . $test->getIdTest() . " ")->getQuery()->getSingleResult();
        }
        else
        {
            return $this->redirectToRoute('test_index');
        }
        return $this->render('test/correct.html.twig', [
            'test' => $test,
            't' => $t,
            'errors'=> $errors  ,
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' => $this->getUser()->getNom(),
            'img' => $this->getUser()->getImg(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idTest}", name="test_delete", methods={"POST"})
     */
    public function delete(Request $request, Test $test): Response
    {
        if ($this->isCsrfTokenValid('delete' . $test->getIdTest(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($test);
            $entityManager->flush();
        }

        return $this->redirectToRoute('test_index');
    }



}
