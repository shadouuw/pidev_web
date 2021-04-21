<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Notification;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()
        ]);
    }

    /**
     * @Route("/new", name="blog_new", methods={"GET","POST"})
     */
    public function new(Request $request , ValidatorInterface $validator): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        $errors=null;

        if($form->isSubmitted())
        {
            $errors= $validator->validate($blog);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setDatePostule(new \DateTime('now'));
            $blog->setNomAdmin($this->getUser()->getNom());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom(),
            'errors' => $errors
        ]);
    }


    /**
     * @Route("/{id}/edit", name="blog_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blog $blog , ValidatorInterface $validator): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        $errors=null;

        if($form->isSubmitted())
        {
            $errors= $validator->validate($blog);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom(),
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/{id}", name="blog_delete", methods={"POST"})
     */
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_index');
    }

    /**
     * @Route("/tri_blog" ,name="tri_blog", methods={"GET"})
     */
    public function index2(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->createQueryBuilder('u')->select('u')->orderBy('u.date_postule' ,'desc ')->getQuery()->getResult(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()

        ]);
    }
    /**
     * @Route("/search_blog", name="search_blog")
     */
    public function search(Request $request, BlogRepository $blogRepository): Response
    {
        $nom = $_GET['nom'];
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->createQueryBuilder('u')->select('u')->where("u.nom = '".$nom."' ")->getQuery()->getResult(),
            'img' => $this->getUser()->getImg(),
            'notifs' => $this->getDoctrine()
                ->getRepository(Notification::class)
                ->findAll(),
            'user' =>  $user = $this->getUser()->getNom()

        ]);
    }


}
