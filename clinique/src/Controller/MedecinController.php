<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedecinController extends AbstractController
{
    /**
     * @Route("/medecin", name="medecin")
     */
    public function index(MedecinRepository $repo)
    {
        $medecin = $repo->findAll();
        return $this->render('medecin/index.html.twig', [
            'medecin' => $medecin,
        ]);
    }
    /**
     * @Route("/medecin/add", name="add.medecin")
     */
    public function addmedecin (MedecinRepository $repo, Request $req )
    {
        $medecin = new Medecin();
        $form = $this->createForm( MedecinType::class, $medecin );
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid() ) {
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($medecin);
           $manager->flush();
           return $this->redirectToRoute('medecin');
        }
        return $this->render('medecin/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/medecin/edit/{id}", name="edit.medecin")
     */
    public function editmedecin ($id,MedecinRepository $repo, Request $req )
    {
        $medecin = $repo->find($id);
        $form = $this->createForm( MedecinType::class, $medecin );
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid() ) {
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($medecin);
           $manager->flush();
           return $this->redirectToRoute('medecin');
        }
        return $this->render('medecin/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
       /**
     * @Route("/medecin/delete/{id}", name="delete.medecin")
     */
    public function deletemedecin ($id,MedecinRepository $repo, Request $req )
    {
        $medecin = $repo->find($id);
       
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($medecin);
        $manager->flush();
        return $this->redirectToRoute('medecin');
        
    }
}
