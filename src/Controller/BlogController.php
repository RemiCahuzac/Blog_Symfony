<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * @param ArticleRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('blog/home.html.twig');
    }

    /**
     * @Route ("/blog/new", name="blog_create")
     * @Route ("/blog/{id}/edit", name="blog_edit")
     * @param Article|null $article
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form(Article $article = null, Request $request,EntityManagerInterface $manager) {

        if(!$article){
            $article = new Article();
        }

        //$form = $this->createFormBuilder($article)
        //    ->add('title')
        //    ->add('content')
        //    ->add('image')
        //    ->getForm();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$article->getId()){
                $article->setCreatedAt((new \DateTime()));
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);

        }

        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route ("/blog/{id}", name="blog_show")
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Article $article){
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }


}
