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
use App\Repository\ConcoursRepository;
use App\Repository\CoursRepository;
use App\Repository\UserRepository;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ZipArchive;
use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Snappy\Pdf;
use Fungio\GoogleMap\Map;
use Symfony\Component\HttpFoundation\Session\Session;
use Fungio\GoogleMap\MapTypeId;
use Fungio\GoogleMap\Helper\MapHelper;

use Symfony\Component\Routing\Annotation\Route;


class FrontController extends AbstractController
{
    private $encoder;
    private $snappy;
    public function __construct(Pdf $snappy , UserPasswordEncoderInterface $encoder)
    {
        $this->snappy = $snappy;
        $this->encoder=$encoder;
    }



    /**
     * @Route("/front", name="front")
     */

    public function index(TestRepository  $testRepository): Response
    {
        $address = 'madrid+spain';

        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');

        $output= json_decode($geocode);
        if($output->status == "OK") {
            $lat = $output->results[0]->geometry->location->lat;
            $long = $output->results[0]->geometry->location->lng;

        }
        else
        {
            $lat=36;
            $long=10;
        }
        $map = new Map();

        $map->setPrefixJavascriptVariable('map_');
        $map->setHtmlContainerId('map_canvas');

        $map->setAsync(false);
        $map->setAutoZoom(false);
    
        $map->setCenter( $lat ,$long, true);
        $map->setMapOption('zoom', 11);

        $map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);

        $map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
        $map->setMapOption('mapTypeId', 'roadmap');

        $map->setMapOption('disableDefaultUI', true);
        $map->setMapOption('disableDoubleClickZoom', true);
        $map->setMapOptions(array(
            'disableDefaultUI'       => true,
            'disableDoubleClickZoom' => true,
        ));

        $map->setStylesheetOption('width', '700px');
        $map->setStylesheetOption('height', '700px');
        $map->setStylesheetOptions(array(
            'width'  => '1000px',
            'height' => '1000px',
        ));

        $map->setLanguage('en');

        $mapHelper = new MapHelper();
        return $this->render('front/hi.html.twig', [
        'controller_name' => 'FrontController',
            'map' =>  $mapHelper->render($map)

    ]);






    }
    /**
     * @Route("/front_log", name="front_log")
     */
    public function sucess(TestRepository $testRepository,UserRepository $userRepository,ConcoursRepository $concoursRepository): Response
    {
        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        if($lol = $testRepository->createQueryBuilder('t')->select('count(t)')->where("t.temps < :date")->andWhere('t.status = 0')->setParameter('date',new \DateTime('now'))->getQuery()->getScalarResult() != 0 )
        {

            $testRepository->createQueryBuilder('t')->update('App\Entity\Test','t')->set('t.status' , '2' )->set('t.note' , '0')->where("t.temps < :date")->andWhere('t.status = 0')->setParameter('date',new \DateTime('now'))->getQuery()->execute();
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('front/sucess.html.twig', [
            'controller_name' => 'FrontController',
            'teacher'=>$userRepository->createQueryBuilder('u')->select('u')->where('u.role = 2')->getQuery()->getResult(),
            'user' =>  $user = $this->getUser()->getNom(),
            'id' => $this->getUser()->getId(),
            'concours' => $concoursRepository->findAll(),
            'cours' => $cours,
            'id_modif' => $this->getUser()->getId()

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
     * @Route("/nadia_front", name="nadia_front")
     */
    public function index33(TestRepository $testRepository): Response
    {
        $cours = $this->getDoctrine()
            ->getRepository(Cours::class)
            ->findAll();

        return $this->render('front/artifical_intelligence.html.twig', [
            'controller_name' => 'FrontController',
            'cours' => $cours,
            'user' => $this->getUser()->getNom(),
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
            ->where('t.note = -1 ')->andWhere('t.status=0 ')->andWhere(" t.idUtilisateur=".$this->getUser()->getId()."")
            ->getQuery()
            ->getSingleScalarResult();
        if ($n != 0) {
            $tests = $testRepository->createQueryBuilder('t')->select('t')->where("t.idUtilisateur = ".$this->getUser()->getId()."")->andWhere('t.status = 0 ')->andWhere('t.note = -1')->getQuery()->getSingleResult();
            $test->setIdTest($tests->getIdTest());
            $form = $this->createForm(Test2Type::class, $tests );
            $form->handleRequest($request);
            $tests = $testRepository->createQueryBuilder('t')->select('t')->where("t.idUtilisateur = ".$this->getUser()->getId()."")->andWhere('t.status = 0 ')->andWhere('t.note = -1')->getQuery()->getSingleResult();
            $test->setIdTest($tests->getIdTest());
            if ($form->isSubmitted() && $form->isValid()) {
                $tests->setStatus(1);
                $test->setIdTest($tests->getIdTest());
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

    /**
     * @Route("/marks", name="marks")
     */
    public function marks(TestRepository $testRepository): Response
    {
        $test= $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findAll();
        $corrected_test=$testRepository->createQueryBuilder('t')->select('avg(t.note)')->where('t.idUtilisateur = '.$this->getUser()->getId().' ')
            ->andWhere('t.status = 2')->getQuery()->getSingleScalarResult();

        return $this->render('front/marks.html.twig', [
            'controller_name' => 'FrontController',
            'user' =>  $user = $this->getUser()->getNom(),
            'id' => $this->getUser()->getId(),
            'avg' =>  $corrected_test,

            'test' =>$testRepository->createQueryBuilder('t')->select('t')->where('t.status = 2 ')->getQuery()->getResult()

            ]);
    }
    /**
     * @Route("/export_pdf", name="export_pdf")
     */
    public function export_pdf(TestRepository $testRepository): Response
    {
        $corrected_test=$testRepository->createQueryBuilder('t')->select('avg(t.note)')->where('t.idUtilisateur = '.$this->getUser()->getId().' ')
            ->andWhere('t.status = 2')->getQuery()->getSingleScalarResult();
        $html = $this->renderView('front/export.html.twig', [
            'controller_name' => 'FrontController',
            'user' =>  $user = $this->getUser()->getNom(),
            'id' => $this->getUser()->getId(),
            'avg' =>  $corrected_test,
            'test' =>$testRepository->createQueryBuilder('t')->select('t')->where('t.status = 2 ')->getQuery()->getResult(

            )


        ]);

//Generate pdf with the retrieved HTML
        return new Response( $this->snappy->getOutputFromHtml($html), 200, array(
            'Content-Type'          => 'application/pdf',
            'Content-Disposition'   => 'inline; filename="export.pdf"'

        ));

    }


    /**
     * @Route("/recup_mdp", name="recup_mdp")
     */
    public function recup_mdp(UserRepository $userRepository): Response
    {


        return $this->render('front/recupere_mdp.html.twig', [
            'controller_name' => 'FrontController',

        ]);
    }
    /**
     * @Route("/write_code", name="write_code")
     */
    public function write_code2(UserRepository $userRepository,Request $request,\Swift_Mailer $mailer): Response
    {
     $s=new Session();

     $mail=$_GET['email'];
        $s->set('email',$mail);
     $n=$userRepository->createQueryBuilder('u')->select('count(u)')
         ->where("u.email = '".$mail."' ")->getQuery()->getSingleScalarResult();


     if($n == 0 )
{
  echo "<script> alert('this mail does not exist !!'); </script>";



    return $this->render('front/recupere_mdp.html.twig', [
        'controller_name' => 'FrontController',
    ]);
}
else {
    $s=rand(1000,9999);
    $message = (new \Swift_Message('new test'))
        ->setFrom('1magicbook1@gmail.com')
        ->setTo($mail)
        ->setBody('The code is '.$s);
    ;
    $userRepository->createQueryBuilder('t')->update('App\Entity\User','u')->set('u.code' , $s )->where("u.email = '".$mail."' ")->getQuery()->execute();
    $mailer->send($message);

    return $this->render('front/confirmation_code.html.twig', [
        'controller_name' => 'FrontController',

    ]);
}
    }

    /**
     * @Route("/confirm_code", name="confirm_code")
     */
    public function confirm_code(UserRepository $userRepository,Request $request): Response
    {
        $code = $_GET['code'];
        $s=new Session();
        $mail=$s->get('email');
        $c=$userRepository->createQueryBuilder('u')
            ->select('u.code')->where("u.email = '".$mail."' ")->getQuery()->getSingleScalarResult();
        if($code == $userRepository->createQueryBuilder('u')
                ->select('u.code')->where("u.email = '".$mail."' ")->getQuery()->getSingleScalarResult()
        )
        {
            return $this->render('front/nouveau_mdp.html.twig', [
                'controller_name' => 'FrontController',

            ]);
        }



        else
        {
            echo "<script> alert('The code is not valid !!'); </script>";
            return $this->render('front/confirmation_code.html.twig', [
                'controller_name' => 'FrontController',

            ]);
        }
    }



/**
 * @Route("/change_password", name="change_password")
 */
public function change_passworde(UserRepository $userRepository,Request $request): Response
{
    $pass1 = $_GET['pass1'];
    $pass2 = $_GET['pass2'];
    $u=new User();
    $s=new Session();
    $mail=$s->get('email');
    if ($pass1 == $pass2) {

        $password=$this->encoder->encodePassword( $u,$pass1);


        $userRepository->createQueryBuilder('u')->update('App\Entity\User','u')->set('u.motDePasse',':password')->set('u.token',':pass' )->setParameter('password',$password)->setParameter('pass',$pass1)->where("u.email = '".$mail."' ")->getQuery()->execute();
        return $this->render('security/login.html.twig', [
            'error' => $error=null,
                'last_username' => $last_username=null,
                'user' => $user=null,
                'password' => $password=null

        ]);


    }
    else
    {
        return $this->render('front/nouveau_mdp.html.twig', [
            'controller_name' => 'FrontController',

        ]);
    }


}

    }



