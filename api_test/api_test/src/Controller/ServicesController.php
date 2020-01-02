<?php

namespace App\Controller;

use App\Entity\Services;
use App\Repository\ServicesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServicesController extends AbstractController
{
    /**
     * @Route("/services", name="services" , methods={"GET"})
     */
    public function index(ServicesRepository $repo, SerializerInterface $serializer)
    {
        $services = $repo->findAll();
        $data = $serializer->serialize($services, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/services", name="services.add", methods={"POST"})
     */
    public function create(Request $request ,SerializerInterface $serializer)
    {
        $services = $serializer->deserialize($request->getContent(),Services::class, 'json');
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($services);
        $manager->flush();
        $data = [
            'status'=> '201',
            'message'=> 'Le service a été bien ajouté'
        ];

        return new JsonResponse($data, 201, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/services/{id}", name="services.edit", methods={"PUT"})
     */
    public function edit(Request $request ,SerializerInterface $serializer, ServicesRepository $repo,$id)
    {
        $service = $repo->find($id);
        $services = $serializer->deserialize($request->getContent(),Services::class, 'json');
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($service);
        $manager->flush();
        $data = [
            'status'=> '201',
            'message'=> 'Le service a été bien ajouté'
        ];

        return new JsonResponse($data, 201, [
            'Content-Type' => 'application/json'
        ]);
    }

}
