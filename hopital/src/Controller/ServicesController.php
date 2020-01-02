<?php

namespace App\Controller;
use App\Entity\Services;
use App\Form\ServiceFormType;
use App\Repository\ServicesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ServicesController extends AbstractController
{
    /**
     * @Route("/services", name="services")
     */
    public function index(ServicesRepository $ripo)
    {
        $service= $ripo->findAll();
        return $this->render('services/index.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/services/addservice", name="addservice")
     */
    public function addService(ServicesRepository $ripo, Request $request)
    {
        $service = new Services();
        $form = $this->createForm(ServiceFormType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($service);
            $manager->flush();
            return $this->redirectToRoute('services');
        }
        return $this->render('services/addservice.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/services/edit/{id}", name="editservice")
     */
    public function editService($id,ServicesRepository $ripo, Request $request)
    {

        $service = $ripo->find($id);
        $form = $this->createForm(ServiceFormType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($service);
            $manager->flush();
            return $this->redirectToRoute('services');
        }
        return $this->render('services/addservice.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
 /**
     * @Route("/services/delete/{id}", name="deleteservice")
     */
    public function deleteService($id,ServicesRepository $ripo, Request $request)
    {

        $service = $ripo->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($service);
        $manager->flush();
        return $this->redirectToRoute('services');
       
    }
    
}
