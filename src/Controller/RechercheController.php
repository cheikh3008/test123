<?php

namespace App\Controller;

use App\Repository\PartenaireRepository;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CompteRepository;

class RechercheController extends AbstractController
{
   /*
    Méthode qui permet de chercher par ninea
   */
    public function rechercheNinea(Request $request, PartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {
        $values = json_decode($request->getContent());
        if($values)
        {
            $partenaire = $partenaireRepository->findByNinea($values->ninea);
            if($partenaire)
            {
                $data = $serializer->serialize($partenaire, 'json');
            
                return new Response($data, 200, [
                    'Content-Type' => 'application/json'
                ]);
            }
            else
            {
                $data = [
                    'status' => 500,
                    'message' => 'Le ninea n\'exixte pas . '];
        
                return new JsonResponse($data, 500);
            }
        }
        else
        {
            $data = [
            'status' => 500,
            'message' => 'Veuillez saisir le ninea . '];

            return new JsonResponse($data, 500);
        }
       
    }

    /*
    Méthode qui permet de chercher par code
   */
    public function rechercheCode(Request $request, TransactionRepository $transactionRepository, SerializerInterface $serializer)
    {
        $values = json_decode($request->getContent());
        if($values)
        {
            $transaction = $transactionRepository->findByCode($values->code);
            if($transaction)
            {
                $data = $serializer->serialize($transaction, 'json');
            
                return new Response($data, 200, [
                    'Content-Type' => 'application/json'
                ]);
            }
            else
            {
                $data = [
                    'status' => 500,
                    'message' => 'Le code n\'exixte pas . '];
        
                return new JsonResponse($data, 500);
            }
        }
        else
        {
            $data = [
            'status' => 500,
            'message' => 'Veuillez saisir le code . '];

            return new JsonResponse($data, 500);
        }
       
    }


    /*
    Méthode qui permet de chercher par numéro de compte
   */
  public function rechercheNumeroCompte(Request $request,CompteRepository $compteRepository, SerializerInterface $serializer) 
  {
    $values = json_decode($request->getContent());
    if($values->numCompte)
    {
        $compte = $compteRepository->findByNumCompte($values->numCompte);
        if($compte)
        {
            $data = $serializer->serialize($compte, 'json');
        
            return new Response($data, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        else
        {
            $data = [
                'status' => 500,
                'message' => 'Le numéro de compte n\'exixte pas . '];
    
            return new JsonResponse($data, 500);
        }
    }
    else
    {
        $data = [
        'status' => 500,
        'message' => 'Veuillez saisir le numéro de compte . '];

        return new JsonResponse($data, 500);
    }
    
    }

}
