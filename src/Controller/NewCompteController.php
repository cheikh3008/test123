<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Partenaire;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class NewCompteController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("api/new/compte", name="new_compte",  methods={"POST"})
     *  @IsGranted({"ROLE_ADMIN", "ROLE_SUPER_ADMIN"})
     */
    public function newCompte(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncode,RoleRepository $roleRepository)
    {
        $values = json_decode($request->getContent());
       
            $dateJours = new \DateTime();
            $user = new User();
            $partenaire = new Partenaire();
            $depot = new Depot();
            $compte = new Compte();
            $role = $roleRepository->findOneBy(array('libelle' => 'ROLE_PARTENAIRE'));

            if($values){
                #### Creation de  Partenaire ####
            
                $partenaire->setNinea($values->ninea)
                    ->setCreatedAt($dateJours)
                    ->setRc($values->rc);
                $manager->persist($partenaire);
            

                #### Creation de User Partenaire ####
                
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
            }else{
                $data = [
                    'status' => 500,
                    'message' => 'Veuillez saisir les valeurs . '];
        
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
