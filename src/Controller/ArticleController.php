<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /*Fonction de recupération de tous les article*/
    #[Route("/",name: "app_articles")]
    public function articles(ArticleRepository $articleRepository){
        $articles = $articleRepository->findAll();
        return $this->render('article/listesArticle.html.twig',array(
            'articles'=>$articles
        ));
    }

    /*Fonction d'ajout d'un article*/
    #[Route('/ajouter', name: 'app_ajouter_livre')]
    public function ajouterArticle(Request $request, EntityManagerInterface $em)
    {
        $article = new Article(); 
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){ 
            $em ->persist($article);
            $em ->flush();
            return $this->redirectToRoute('app_articles');
        }
        return $this->render('article/ajouterArticle.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /*Fonction de modification d'un Article*/
    #[Route("/modifier/{id<\d+>}", name: "app_modifier_livre")]
    public function modifierArticle(Request $request, Article $article, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_articles');
        }
        return $this->render('article/modifierArticle.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /*Fonction de suppression d'un article*/
    #[Route("/supprimer/{id<\d+>}", name: "app_supprimer_livre")]
    public function supprimerArticle(Article $article, EntityManagerInterface $em)
    {
        $em ->remove($article);
        $em ->flush();
        return $this->redirectToRoute('app_articles'); 
    }

}
