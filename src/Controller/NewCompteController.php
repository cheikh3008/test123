<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Contrat;
use App\Entity\Partenaire;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\IsTrue;

class NewCompteController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("api/compte/partenaire/new", name="new_compte",  methods={"POST"})
     * @IsGranted({"ROLE_ADMIN", "ROLE_SUPER_ADMIN"})
     */
    public function comptePartenaireNew(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncode,RoleRepository $roleRepository,PartenaireRepository $partenaireRepository, UserRepository $userRepository,SerializerInterface $serializer)
    {
        $values = json_decode($request->getContent());
        
        $dateJours = new \DateTime();
        $user = new User();
        $depot = new Depot();
        $compte = new Compte();
        $role = $roleRepository->findOneBy(array('libelle' => 'ROLE_PARTENAIRE'));
        $mail = $userRepository->findOneBy(array("email"=>$values->email));
        if($mail !== null)
        {
            $data = [
                'status' => 500,
                'message' => 'Cet adresse email existe déja . '];
    
            return new JsonResponse($data, 500);
        }
        if($values){
       
            if($values->montant < 500000){
                $data = [
                    'status' => 500,
                    'message' => 'Veuillez déposer minimun 500 000 F pour un nouveau compte . '];
        
                return new JsonResponse($data, 500);
            
            }
            $partenaire_existant = $partenaireRepository->findOneBy(array('ninea' => $values->ninea));
            
            
            if($partenaire_existant === null)
            {
                $partenaire = new Partenaire();
                $contrat = new Contrat();
                #### Creation de  Partenaire ####
        
                $partenaire->setNinea($values->ninea)
                    ->setRc($values->rc)
                    ->setContrat($contrat);
                $manager->persist($partenaire);
                
                #### Creation de contrat Partenaire ####
                $contrat->setTermes("Un contrat de partenariat est un document juridique qui définit la structure légale de l'entité du partenariat. Il décrit tous les termes, conditions, responsabilités, les parts de propriété, profits et pertes dans l'entreprise, et constitue les règles régissant l'activité commerciale.")
                        ->setCreatedAt($dateJours);
                $manager->persist($contrat);
    
                $user->setEmail($values->email)
                    ->setPassword($passwordEncode->encodePassword($user, $values->password))
                    ->setRole($role)
                    ->setPrenom($values->prenom)
                    ->setNom($values->nom)
                    ->setPartenaire($partenaire);
                $manager->persist($user);
    
                #### Générer le numéro de compte #####
    
                $compte_id = $this->getLastId() + 1 ;
                $numCompte =str_pad($compte_id, 9 ,"0",STR_PAD_LEFT);
                
                #### Creation de compte Partenaire ####
    
                $userCreateur = $this->tokenStorage->getToken()->getUser();
                $compte->setNumCompte($numCompte)
                    ->setSolde(0)
                    ->setCreatedAt($dateJours)
                    ->setUserCreateur($userCreateur)
                    ->setPartenaire($partenaire);
                $manager->persist($compte);
                
                ##### Initiliasation du compte ####
    
                $depot->setCreatedAt($dateJours)
                    ->setMontant($values->montant)
                    ->setUserDepot($userCreateur)
                    ->setCompte($compte);
                $manager->persist($depot);
    
                #### Mise à jour du compte #####
    
                $NouveauSolde = ($values->montant+$compte->getSolde());
                $compte->setSolde($NouveauSolde);
                $manager->persist($compte);
                $manager->flush();
                $data = [
                    'status' => 201,
                    'message' => 'Le compte a été bien creé . '
                ];
                return new JsonResponse($data, 201);
                
            }
            else
            {
                $data = [
                    'status' => 500,
                    'message' => 'Ce partenaire existe déja'
                ];
                return new JsonResponse($data, 500);
            }
            
        }else{
            $data = [
                'status' => 500,
                'message' => 'Veuillez saisir les valeurs . '];
    
            return new JsonResponse($data, 500);
        }
                    
    }

    /**
     * @Route("api/compte/partenaire/existant", name="existant_compte",  methods={"POST"})
     *  @IsGranted({"ROLE_ADMIN", "ROLE_SUPER_ADMIN"})
     */
    public function comptePartenaireExistant(Request $request, EntityManagerInterface $manager,PartenaireRepository $partenaireRepository) 
    {
        $values = json_decode($request->getContent());
        $partenaire_existant = $partenaireRepository->findOneBy(array('ninea' => $values->ninea));
        $dateJours = new \DateTime();
        $user = new User();
        $depot = new Depot();
        $compte = new Compte();
        if($values)
        {
            if($partenaire_existant !== null)
            {
                #### Générer le numéro de compte #####
        
                $compte_id = $this->getLastId() + 1 ;
                $numCompte =str_pad($compte_id, 9 ,"0",STR_PAD_LEFT);
                
                #### Creation de compte Partenaire ####

                $userCreateur = $this->tokenStorage->getToken()->getUser();
            
                $compte->setNumCompte($numCompte)
                    ->setSolde(0)
                    ->setCreatedAt($dateJours)
                    ->setUserCreateur($userCreateur)
                    ->setPartenaire($partenaire_existant);
                $manager->persist($compte);
                
                ##### Initiliasation du compte ####

                $depot->setCreatedAt($dateJours)
                    ->setMontant($values->montant)
                    ->setUserDepot($userCreateur)
                    ->setCompte($compte);
                $manager->persist($depot);

                #### Mise à jour du compte #####

                $NouveauSolde = ($values->montant+$compte->getSolde());
                $compte->setSolde($NouveauSolde);
                $manager->persist($compte);
                $manager->flush();
                $data = [
                    'status' => 201,
                    'message' => 'Ce partenaire existe déja et un nouveau compte a été bien creé pour lui . '
                ];
                return new JsonResponse($data, 201);
            }
            else
            {
                $data = [
                    'status' => 500,
                    'message' => 'Ce partenaire n\' existe pas . '];
        
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
    ### Get last Partenaire ###
    public function getLastId() 
    {
        $repository = $this->getDoctrine()->getRepository(Compte::class);
        // look for a single Product by name
        $res = $repository->findBy([], ['id' => 'DESC']) ;
        if($res == null){
            return 0;
        }else{
            return $res[0]->getId();
        }
        
    }  
}
