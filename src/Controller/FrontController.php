<?php

namespace App\Controller;
use App\Entity\Blog;
use App\Entity\Commentaire;
use App\Entity\Test;
use App\Entity\User;
use App\Entity\Cours;
use App\Form\CommentaireType;
use App\Form\TestType;
use App\Form\Test2Type;
use App\Repository\BlogRepository;
use App\Repository\CommentaireRepository;
use App\Repository\CoursRepository;
use ZipArchive;
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




        $docObj = new DocxConversion("Ines.docx");
        $doc=$docObj->read_docx("Ines.docx");


        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('front/hi.html.twig', [
            'controller_name' => 'FrontController',
            'cours' => $cours,
            'user' => $user,
            'word' => $doc


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
     * @Route("/blog_front", name="blog_front")
     */
    public function blog(): Response
    {
        $blog = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findAll();

        return $this->render('front/blog.html.twig', [
            'controller_name' => 'FrontController',
            'user' =>  $user = $this->getUser()->getNom(),
            'id' => $this->getUser()->getId(),
            'blogs' => $blog

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
     * @Route("/cours_detail/{id}", name="cours_detail")
     */
    public function index6(CoursRepository $coursRepository,Cours $c): Response
    {$c=$coursRepository->createQueryBuilder('c')->select('c')->where("c.idCours = ".$c->getIdCours()." ")->getQuery()->getSingleResult();
        $cours = $this->getDoctrine()

            ->getRepository(Cours::class)
            ->findAll();
        $docObj = new DocxConversion();
        $doc=$docObj->read_docx($c->getLien2());
        return $this->render('front/cours_detail.html.twig', [
            'controller_name' => 'FrontController',
            'cours' => $cours,
            'c' => $coursRepository->createQueryBuilder('c')->select('c')->where("c.idCours = ".$c->getIdCours()." ")->getQuery()->getSingleResult(),
            'id' => $this->getUser()->getId(),
            'word' => $doc
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
            $tests = $testRepository->createQueryBuilder('t')->select('t')->where("t.idUtilisateur = ".$this->getUser()->getId()."")->andWhere('t.status = 0 ')->andWhere('t.note = -1')->getQuery()->getSingleResult();

            if ($form->isSubmitted() && $form->isValid()) {
                $test->setStatus(1);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('front_log');
            }
            $cours = $this->getDoctrine()
                ->getRepository(Cours::class)
                ->findAll();


            return $this->render('front/test.html.twig', [
                'tests' => $tests,
                'test' => $test,
                'cours' => $cours,
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

    /**
     * @Route("/blog_detail/{id}", name="blog_detail")
     */
    public function blog_d(Request $request,BlogRepository $blogRepository,Blog $b,CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setDate(new \DateTime('now'));
            $commentaire->setIdblog($b->getId());
            $commentaire->setEmail($this->getUser()->getEmail());
            $commentaire->setIduser($this->getUser()->getId());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

        }

  $comments=$commentaireRepository->createQueryBuilder('c')->select('c')->where("c.idblog = ".$b->getId()."")->getQuery()->getResult();
  $n=$commentaireRepository->createQueryBuilder('c')->select('count(c)')->where("c.idblog = ".$b->getId()."")->getQuery()->getSingleScalarResult();

        return $this->render('front/blog_detail.html.twig', [
            'controller_name' => 'FrontController',
            'blog' => $blogRepository->createQueryBuilder('c')->select('c')->where("c.id = ".$b->getId()." ")->getQuery()->getSingleResult(),
            'id' => $this->getUser()->getId(),
            'comments' => $comments,
            'users' => $this->getDoctrine()
                ->getRepository(User::class)
                ->findAll(),'form' => $form->createView(),

        ]);
    }




}

