<?php

namespace App\Controller;

namespace App\Controller;
use App\Entity\Services;
use App\Form\ServicesType;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ServicesController extends AbstractController
{
    /**
     * @Route("/services", name="services")
     */
    public function index( ServicesRepository $repo )
    {
        $service = $repo->findAll();
        return $this->render('service/index.html.twig', [
            'service' => $service,
        ]);
    }

     /**
     * @Route("/services/add", name="add")
     */
    public function addService (ServicesRepository $repo, Request $req )
    {
        $service = new Services();
        $form = $this->createForm( ServicesType::class, $service );
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid() ) {
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($service);
           $manager->flush();
           return $this->redirectToRoute('services');
        }
        return $this->render('service/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
     /**
     * @Route("/services/edit/{id}", name="edit")
     */
    public function editService ($id,ServicesRepository $repo, Request $req )
    {
        $service = $repo->find($id);
        $form = $this->createForm( ServicesType::class, $service );
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid() ) {
           $manager = $this->getDoctrine()->getManager();
           $manager->persist($service);
           $manager->flush();
           return $this->redirectToRoute('services');
        }
        return $this->render('service/add.html.twig',[
            'form' => $form->createView()
        ]);
    }
      /**
     * @Route("/services/delete/{id}", name="delete")
     */
    public function deleteService ($id,ServicesRepository $repo, Request $req )
    {
        $service = $repo->find($id);
       
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($service);
        $manager->flush();
        return $this->redirectToRoute('services');
        
        
    }
}
