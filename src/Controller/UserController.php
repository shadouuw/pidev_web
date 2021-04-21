<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Entity\User;
use App\Form\User1Type;
use App\Form\UserType;
use App\Repository\UserRepository;
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
    public function new(Request $request,ValidatorInterface $validator): Response
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
            $password=$this->encoder->encodePassword($user,$user->getPassword());

            $user->setMotDePasse($password);

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
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
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







}
