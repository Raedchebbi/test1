<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EtudiantRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\EtudiantType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class EtudiantController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant')]
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }

    #[Route("/data", name:"data_app")]
    public function data(EtudiantRepository $ar):Response{
        $list=$ar->findAll();
        return $this->render("etudiant/afficher.html.twig", ['list'=>$list]);

    }

    #[Route('/Add',name:'etudiant_add')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):response
    {
        $etudiant = new \App\Entity\Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
        $em=$doctrine->getManager();
        $em->persist($etudiant); 
        $em->flush();
        return $this->redirectToRoute('data_app');
        }
             return $this->render('etudiant/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[route('/delete/{id}',name:'app_delete')]
    public function delete(EtudiantRepository $repoEtud,int $id,EntityManagerInterface $entityManager):Response{
        $auth=$repoEtud->find($id);
        $entityManager->remove($auth);
         $entityManager->flush();
        return $this->redirectToRoute('data_app');
    }

    #[Route('/Update/{id}',name:'etudiant_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,EtudiantRepository $repoEtud):response
    {
        $author=$repoEtud->find($id);
        $form=$this->createForm(EtudiantType::class,$author);
        $form->handleRequest($request);
       if ($form->isSubmitted() ) 
       {
        $em=$doctrine->getManager(); 
        $em->flush();
        return $this->redirectToRoute('data_app');
    }
    return $this->render('etudiant/update.html.twig',['form'=>$form->createView()]) ;
    
    }









}
