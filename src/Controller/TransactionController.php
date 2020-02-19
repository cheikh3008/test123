<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffectationRepository;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\TarifRepository;
use App\Repository\TransactionRepository;

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
    public function envoi(Request $request,EntityManagerInterface $manager, AffectationRepository $affectationRepository, CompteRepository $compteRipo, TarifRepository $tarifRepository)    {
        $userEnvoi = $this->tokenStorage->getToken()->getUser();
        $values = json_decode($request->getContent());
        $userPartenaire = $userEnvoi->getPartenaire();
        $userAffcete = $affectationRepository->findOneBy(array("user"=>$userEnvoi));
        
        if($userAffcete){
            
            $compte = $userAffcete->getCompte();
            $compte = $compteRipo->findOneBy(array("id"=>$compte));
    
        }
        elseif($compte = $compteRipo->findBy(array("partenaire" => $userPartenaire))){
           
                dd($compte);
            
        }else{
            $data = [
                'status' => 500,
                'message' => 'Aucun compte n\'est affecté à cet utlisateur . '];
    
            return new JsonResponse($data, 500);
        }
        
        $tarif = $tarifRepository->findAll();
        
            foreach ($tarif as $res)
            {
                $res->getBorneInf();
                $res->getBorneSup();
                $res->getFrais();
                if($values->montant >= $res->getBorneInf() && $values->montant <= $res->getBorneSUp() ){
                    $frais =  $res->getFrais();

                }
                
            }
        if($values->montant > $compte->getSolde()){
            $data = [
                'status' => 201,
                'message' => "Opération échouée, le solde est insuffisant ... "
            ];
    
                return new JsonResponse($data, 201);
        }
        $comEtat = $frais * 30/100;
        $comSysteme = $frais * 40/100 ;
        $comEnvoi = $frais * 10/100 ;
        $comRetrait = $frais * 20/100 ;
        $code = rand(0,999999999) +1 ;
            
        $envoi = new Transaction();
        $dateEnvoi = new \DateTime();
         #### Faire un envoi ####
        if($values){
            
            $envoi->setPrenomE($values->prenomE)
                        ->setnomE($values->nomE)
                        ->setTelephoneE($values->telephoneE)
                        ->setNpieceE($values->npieceE)
                        ->setDateEnvoi($dateEnvoi)
                        ->setPrenomB($values->prenomB)
                        ->setNomB($values->nomB)
                        ->setTelephoneB($values->telephoneB)
                        ->setMontant($values->montant)
                        ->setFrais($frais)
                        ->setComEtat($comEtat)
                        ->setComEnvoi($comEnvoi)
                        ->setComSysteme($comSysteme)
                        ->setEnvoi($compte)
                        ->setUserEnvoi($userEnvoi)
                        ->setCode($code);           
            $manager->persist($envoi);
            dd($envoi);
            ##### Mise à jour du solde #######

            $NouveauSolde = ($compte->getSolde() - $values->montant );
            $compte->setSolde($NouveauSolde);
            $manager->persist($compte);

            $manager->flush();
            $data = [
                'status' => 201,
                'message' => 'Vous avez enoyé '.$values->montant. ' à '. $values->prenomE.' - '. $values->nomE .' - ' ];
    
                return new JsonResponse($data, 201);
        }else{
            $data = [
                'status' => 500,
                'message' => 'Veuillez saisir tous les champs ... '
                ] ;
    
                return new JsonResponse($data, 500);
        }
       
    }
    /**
     * @Route("/api/transaction/retrait", name="retrait", methods={"POST"})
     */
    public function retrait(Request $request,EntityManagerInterface $manager, AffectationRepository $affectationRepository, CompteRepository $compteRipo, TransactionRepository $transactionRepository)
    {
        $values = json_decode($request->getContent());
        $code = $transactionRepository->findOneBy(array("code" => $values->code));
        if($code){
            $userRetrait = $this->tokenStorage->getToken()->getUser();
            $dateRetrait = new \DateTime();
            $retrait = new Transaction();
            
            if($res = $transactionRepository->findOneBy(array("etat"=> $code->getEtat()))){
                
                dd($res);
            }
            $retrait->setDateRetrait($dateRetrait)
                    ->setEtat(true)
                    ->setNpieceB($values->npieceB)
                    ->setRetrait($compte)
                    ->setComRetrait($comRetrait)
                    ->setUserRetrait($userRetrait);
            $manager->persist($retrait);
            $data = [
                'status' => 201,
                'message' => 'Vous avez enoyé retiré '];
    
                return new JsonResponse($data, 201);

       }else {
        $data = [
            'status' => 201,
            'message' => 'Le code saisit est incorrect ...' ];

            return new JsonResponse($data, 201);
       }
    }
}
