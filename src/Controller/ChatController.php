<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Notification;
use App\Entity\User;
use App\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chat")
 */
class ChatController extends AbstractController
{
    /**
     * @Route("/", name="chat_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $chats=$this->getDoctrine()->getManager()->createQuery("select distinct u2 from App\Entity\User u,App\Entity\User u2, App\Entity\Chat c where ((c.userEmut=u2.id and c.userReceiv=u.id) or (c.userEmut=u.id and c.userReceiv=u2.id)) and  u.nomUtilisateur='".$user->getUsername()."'")->getResult();

        $listeUsers=$this->getDoctrine()->getManager()->createQuery("select distinct u from App\Entity\User u where  u.nomUtilisateur <>'".$user->getUsername()."'")->getResult();



        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

            return $this->render('chat/frontindex.html.twig', [
                'chats' => $chats,

                'img' => $this->getUser()->getImg(),
                'notifs' => $this->getDoctrine()
                    ->getRepository(Notification::class)
                    ->findAll(),
                'user' => $user = $this->getUser()->getNom(),
                'allusers' => $listeUsers,
                'id' => $this->getUser()->getId(),
            ]);
        }else{
            return $this->render('chat/index.html.twig', [
                'chats' => $chats,

                'img' => $this->getUser()->getImg(),
                'notifs' => $this->getDoctrine()
                    ->getRepository(Notification::class)
                    ->findAll(),
                'user' => $user = $this->getUser()->getNom(),
                'allusers' => $listeUsers,

            'id' => $this->getUser()->getId(),
            ]);
        }
    }

    /**
     * @Route("/new", name="chat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $chat = new Chat();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chat);
            $entityManager->flush();

            return $this->redirectToRoute('chat_index');
        }

        return $this->render('chat/new.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chat_show", methods={"GET"})
     */
    public function show(Chat $chat): Response
    {
        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="chat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Chat $chat): Response
    {
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chat_index');
        }

        return $this->render('chat/edit.html.twig', [
            'chat' => $chat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="chat_delete", methods={"POST"})
     */
    public function delete(Request $request, Chat $chat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($chat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('chat_index');
    }

    /**
     * @Route("/details/{id}", name="chat_messagedetails", methods={"GET"})
     */
    public function messagedetails(Request $request, User $user_receiv): Response
    {
        $_userConnect = $this->getUser();
        $userConnect= $this->getDoctrine()->getRepository(User::class)->findOneBy(["nomUtilisateur"=>$_userConnect->getUsername()]);
//findOneBy(["commande"=>$cmd]);
        $requet="select distinct c from App\Entity\Chat c where (c.userEmut=". $user_receiv->getId()." and c.userReceiv=".$userConnect->getId().  ") or (c.userReceiv=".$user_receiv->getId()." and c.userEmut=".$userConnect->getId() .") order by c.dateenvoi ";

        $chats=$this->getDoctrine()->getManager()->createQuery($requet )->getResult();


        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->render('chat/frontopenmessage.html.twig', [
                'chats' => $chats,

                'img' => $this->getUser()->getImg(),
                'notifs' => $this->getDoctrine()
                    ->getRepository(Notification::class)
                    ->findAll(),
                'userConnect' => $userConnect,
                'userreceiv' => $user_receiv,
                'id' => $this->getUser()->getId()
            ]);
        }else{
            return $this->render('chat/openmessage.html.twig', [
                'chats' => $chats,

                'img' => $this->getUser()->getImg(),
                'notifs' => $this->getDoctrine()
                    ->getRepository(Notification::class)
                    ->findAll(),
                'userConnect' => $userConnect,
                'userreceiv' => $user_receiv,
                'id' => $this->getUser()->getId()
            ]);
        }

    }


    /**
     * @Route("/chat_messagedetailsenovyer/", name="chat_messagedetailsenovyer")
     */
    public function chat_messagedetailsenovyer(Request $request): Response
    {
        $msg = $request->get('msg');
        $userRec = $request->get('userreceiv');
        $userEmis = $request->get('userConnect');

        $Connecter= $this->getDoctrine()->getRepository(User::class)->find($userEmis);
        $Receiver= $this->getDoctrine()->getRepository(User::class)->find($userRec);

        $chat=new Chat();
        $chat->setMessage($msg);
        $chat->setUserEmut($Connecter);
        $chat->setUserReceiv($Receiver);
        $chat->setVu(false);
        $chat->setDateenvoi(new \DateTime());


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($chat);
        $entityManager->flush();

        //redirection
        $_userConnect = $this->getUser();
        $userConnect= $this->getDoctrine()->getRepository(User::class)->findOneBy(["nomUtilisateur"=>$_userConnect->getUsername()]);
//findOneBy(["commande"=>$cmd]);
        $requet="select distinct c from App\Entity\Chat c where (c.userEmut=". $userRec." and c.userReceiv=".$userConnect->getId().  ") or (c.userReceiv=".$userRec." and c.userEmut=".$userConnect->getId() .") order by c.dateenvoi ";

        $chats=$this->getDoctrine()->getManager()->createQuery($requet )->getResult();


        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->render('chat/frontopenmessage.html.twig', [
                'chats' => $chats,

                'img' => $this->getUser()->getImg(),
                'notifs' => $this->getDoctrine()
                    ->getRepository(Notification::class)
                    ->findAll(),
                'userConnect' => $userConnect,
                'userreceiv' => $Receiver,
                'id' => $this->getUser()->getId(),
            ]);
        }else{
            return $this->render('chat/openmessage.html.twig', [
                'chats' => $chats,

                'img' => $this->getUser()->getImg(),
                'notifs' => $this->getDoctrine()
                    ->getRepository(Notification::class)
                    ->findAll(),
                'userConnect' => $userConnect,
                'userreceiv' => $Receiver,
                'id' => $this->getUser()->getId(),
            ]);
        }

    }


}
