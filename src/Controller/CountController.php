<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\DepotRepository;
use App\Repository\CompteRepository;
use App\Repository\PartenaireRepository;
use App\Repository\TransactionRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CountController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/api/count", name="count", methods={"GET"})
     */
    public function compter(CompteRepository $compte, UserRepository $user, TransactionRepository $transactionRepository, DepotRepository $depotRepository)
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $roleUser = $userConnecte->getRole()->getLibelle();
        $partenaire_id = $userConnecte->getPartenaire();
        if($roleUser === "ROLE_PARTENAIRE" || $roleUser === "ROLE_ADMIN_PARTENAIRE" )
        {

            $data [] = [
                "compte" => count($compte->findBy(array("partenaire" => $partenaire_id))),
                "user" => count($user->findBy(array("partenaire"=>$partenaire_id))) - 1,
                "envoi" => count($transactionRepository->findByEnvois($partenaire_id)),
                "retrait" => count($transactionRepository->findByRetraits($partenaire_id)),

            ];
            
        }
        elseif ($roleUser === "ROLE_SUPER_ADMIN" || $roleUser === "ROLE_ADMIN" ) 
        {
            $data [] = [
                "partenaire" => count($user->findByPartenaire()),
                "user" => count($user->findUsersBySupAdmin()),
                "compte" => count($compte->findAll()),
                "depot" => count($depotRepository->findAll()),

            ];
        }
        else
        {
            $data [] = [];
        }
        return new JsonResponse($data, 200);
    }
    /**
     * @Route("/api/recherche/parts/envois", name="parts_envois", methods={"POST"})
     */
    public function recherchePartsEnvois(TransactionRepository $transactionRepository, Request $request, SerializerInterface $serializer, PartenaireRepository $partenaire) 
    {
        $values = json_decode($request->getContent());
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $roleUser = $userConnecte->getRole()->getLibelle();
        
        $dd = (\DateTime::createFromFormat('Y-m-d', $values->dateDebut));
        $df = (\DateTime::createFromFormat('Y-m-d', $values->dateFin));
        if($roleUser === "ROLE_SUPER_ADMIN" || $roleUser === "ROLE_ADMIN" )
        {
            $ninea = $partenaire->findOneBy(array("ninea" => $values->ninea));
            $data = $transactionRepository->recherchePartsByEnvoi($dd, $df, $ninea->getNinea());
            $result= $serializer->serialize($data, 'json');
        }
        
        else
        {
            $result = [];
        }

        return new Response($result, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/api/recherche/parts/retraits", name="parts_retraits", methods={"POST"})
     */
    public function recherchePartsRetraits(TransactionRepository $transactionRepository, Request $request, SerializerInterface $serializer, PartenaireRepository $partenaire) 
    {
        $values = json_decode($request->getContent());
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $roleUser = $userConnecte->getRole()->getLibelle();
        
        $dd = (\DateTime::createFromFormat('Y-m-d', $values->dateDebut));
        $df = (\DateTime::createFromFormat('Y-m-d', $values->dateFin));
        if($roleUser === "ROLE_SUPER_ADMIN" || $roleUser === "ROLE_ADMIN" )
        {
            $ninea = $partenaire->findOneBy(array("ninea" => $values->ninea));
            $data = $transactionRepository->recherchePartsByRetrait($dd, $df, $ninea->getNinea());
            $result= $serializer->serialize($data, 'json');
        }
        
        else
        {
            $result = [];
        }

        return new Response($result, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/api/list/parts", name="part_partenaire", methods={"GET"})
    */
    public function listerPartPartenaire(TransactionRepository $transactionRepository) 
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $roleUser = $userConnecte->getRole()->getLibelle();
        $partenaire_id = $userConnecte->getPartenaire();
        if($roleUser === "ROLE_ADMIN_PARTENAIRE" || $roleUser === "ROLE_PARTENAIRE" )
        {
            if($partenaire_id)
            {
                $result = $transactionRepository->findByPartEnvoisPartenaire($partenaire_id);
            }
             
        }
        elseif($roleUser === "ROLE_ADMIN" || $roleUser === "ROLE_SUPER_ADMIN" )
        {
            $result = $transactionRepository->findByPartSysteme();
        }
        else
        {
            $data = [
                'status' => 500,
                'message' => 'Vous n\'êtes autorisés accéder à ce service. '
            ] ;
    
            return new JsonResponse($data, 500);
        }
        return new JsonResponse($result, 200);
    }
}
