<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{

    #[Route("/categorie",name: "categorie")]
    public function catgories(CategorieRepository $categorieRepository){
        $categories = $categorieRepository->findAll();
        return $this->render('categorie/listesCategorie.html.twig',array(
            'categories'=>$categories
        ));
    }

      /*Fonction d'ajout d'un article*/
      #[Route('/ajouter_categorie', name: 'app_ajouter_categorie')]
      public function ajouterCategorie(Request $request, EntityManagerInterface $em)
      {
          $categorie = new Categorie(); 
          $form = $this->createForm(CategorieType::class, $categorie);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){ 
              $em ->persist($categorie);
              $em ->flush();
              return $this->redirectToRoute('categorie');
          }
          return $this->render('categorie/ajouterCategorie.html.twig',array(
              'form'=>$form->createView()
          ));
      }

    
    #[Route("/supprimer_categorie/{id<\d+>}", name: "app_supprimer_categorie")]
    public function supprimerCategorie(Categorie $categorie, EntityManagerInterface $em)
    {
        $em ->remove($categorie);
        $em ->flush();
        return $this->redirectToRoute('categorie'); 
    }

      /*Fonction de modification d'un Article*/
      #[Route("/modifier_categorie/{id<\d+>}", name: "app_modifier_categorie")]
      public function modifierCategorie(Request $request, Categorie $categorie, EntityManagerInterface $em)
      {
          $form = $this->createForm(CategorieType::class, $categorie);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()){
              $em->flush();
              return $this->redirectToRoute('categorie');
          }
          return $this->render('categorie/modifierCategorie.html.twig',array(
              'form'=>$form->createView()
          ));
      }
}
