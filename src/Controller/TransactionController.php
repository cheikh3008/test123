<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

    }
    /**
     * @Route("/api/transaction/envoi", name="envoi", methods={"POST"})
     */
    public function envoi(Request $request,EntityManagerInterface $manager, AffectationRepository $affectationRepository, CompteRepository $compteRipo)
    {
        $userCreateur = $this->tokenStorage->getToken()->getUser();
        $values = json_decode($request->getContent());

        $entUser = $affectationRepository->findOneBy(array("user"=>$userCreateur));
        $compte= ($entUser->getCompte());
        $compt = $compteRipo->findOneBy(array("id"=>$compte));
        

        
       
        $transaction = new Transaction();
        $dateEnvoi = new \DateTime();
         #### Faire un envoi ####
        if($values){
            $code = rand(0,999999999);
            $transaction->setPrenomE($values->prenomE)
                        ->setnomE($values->nomE)
                        ->setTelephoneE($values->telephoneE)
                        ->setNpieceE($values->npieceE)
                        ->setDateEnvoi($dateEnvoi)
                        ->setPrenomB($values->prenomB)
                        ->setNomB($values->nomB)
                        ->setTelephoneB($values->telephoneB)
                        ->setMontant($values->montant)
                        ->setFrais($values->frais)
                        ->setEnvoi($compt)
                        ->setUserEnvoi($userCreateur)
                        ->setCode($code);
                        
            $manager->persist($transaction);
            dd($transaction);
            $manager->flush();
            $data = [
                'status' => 201,
                'message' => 'Vous avez déposé '.$values->montant.' dans votre compte => '];
    
                return new JsonResponse($data, 201);
        }
       
    }
}
