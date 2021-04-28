<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Form\User1Type;
use App\Form\User2Type;
use App\Form\UserType;
use App\Repository\UserRepository;
use Fungio\GoogleMap\Helper\MapHelper;
use Fungio\GoogleMap\Map;
use Fungio\GoogleMap\MapTypeId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    private $encoder;
    public  function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'user' => $this->getUser()->getNom(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,ValidatorInterface $validator,UserRepository $userRepository): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            {
                return $this->redirectToRoute('front_log');
            }
            else {
                return $this->redirectToRoute('cours_c_index');
            }
        }
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);
        $errors = $validator->validate($user);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            echo $user->getPassword();

            $user->setToken($user->getPassword());
            $password=$this->encoder->encodePassword($user,$user->getPassword());

            $user->setMotDePasse($password);
            $user->setRole(0);
            $user->setStatus(1);


            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('login');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user->getNom(),
            'id' => $user->getId(),
            'form' => $form->createView(),
            'errors' => $errors

        ]);
    }


    /**
     * @Route("/new2", name="user_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {

        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            echo $user->getPassword();
            $user->setToken($user->getPassword());
            $user->setToken($user->getPassword());
            $password=$this->encoder->encodePassword($user,$user->getPassword());

            $user->setMotDePasse($password);
            $user->setStatus(1);
            $user->setRole(2);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new_back.html.twig', [
            'user' => $user->getNom(),
            'form' => $form->createView(),
            'img' =>$this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),

        ]);
    }




    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'users' => $user,
            'user' => $this->getUser()->getNom(),
            'img' =>$this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),

            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}/edit2", name="user_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, User $user , ValidatorInterface $validator): Response
    {
        $errors=null;
        $form = $this->createForm(User2Type::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $errors = $validator->validate($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $password=$this->encoder->encodePassword($user,$user->getToken());
            $user->setMotDePasse($password);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('front_log');
        }

        return $this->render('user/edit2.html.twig', [
            'users' => $user,
            'user' => $this->getUser()->getNom(),
            'img' =>$this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'errors' =>$errors,
            'id' => $this->getUser()->getId(),

            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }


    /**
     * @Route("/{id}/block", name="user_block", methods={"GET","POST"})
     */
    public function block(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $user->setStatus(0);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/confirm", name="user_confirm", methods={"GET","POST"})
     */
    public function confirm(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $user->setStatus(1);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/user_tri", name="user_tri", methods={"GET"})
     */
    public function index4(UserRepository $userRepository): Response
    {
        $users = $userRepository->createQueryBuilder('u')->select('u')->orderBy('u.age')->getQuery()->getResult();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'user' => $this->getUser()->getNom(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'img' => $this->getUser()->getImg()
        ]);
    }

    /**
     * @Route("/{id}/map", name="user_map", methods={"GET","POST"})
     */
    public function map(Request $request, User $user): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);
        $address=$user->getAdresse();

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


        return $this->render('user/map.html.twig', [
            'users' => $user,
            'adresse' => $address,
            'user' => $this->getUser()->getNom(),
            'img' =>$this->getUser()->getImg(),
            'map' =>  $mapHelper->render($map),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),

        ]);
    }






}
