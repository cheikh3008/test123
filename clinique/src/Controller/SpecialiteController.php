<?php

namespace App\Controller;

use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Repository\SpecialiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpecialiteController extends AbstractController
{
    /**
     * @Route("/specialite", name="specialite")
     */
    public function index(SpecialiteRepository $repo)

    {
        $specialite = $repo->findAll();
        return $this->render('specialite/index.html.twig', [
            'specialite' => $specialite,
        ]);
    }

    /**
     * @Route("/specialite/add", name="add.specialite")
     */

    public function addSpecialite(SpecialiteRepository $repo,Request $req)

    {
        $specialite = new Specialite();
        $form = $this->createForm(SpecialiteType::class,$specialite);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($specialite);
            $manager->flush();

            return $this->redirectToRoute('specilaite');
        }
        return $this->render('specialite/add.html.twig', [
            'specialite' => $specialite,
            'form'=> $form->createView(),
        ]);
    }
    /**
     * @Route("/specialite/edit/{id}", name="edit.specialite")
     */

    public function editSpecialite($id,SpecialiteRepository $repo,Request $req)

    {
        $specialite = $repo->find($id);
        $form = $this->createForm(SpecialiteType::class,$specialite);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($specialite);
            $manager->flush();

            return $this->redirectToRoute('specialite');
        }
        return $this->render('specialite/add.html.twig', [
            'form'=> $form->createView(),
        ]);
    }
      /**
     * @Route("/specialite/delete/{id}", name="delete.specialite")
     */
    public function deleteSpecialite ($id,SpecialiteRepository $repo, Request $req )
    {
        $specialite = $repo->find($id);
       
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($specialite);
        $manager->flush();
        return $this->redirectToRoute('specialite');
        
        
    }
}
