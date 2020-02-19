<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BloquerPartenaireController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage )
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/api/bloquer/{id}", name="bloquer_partenaire", methods={"PUT"})
     * @IsGranted({"ROLE_ADMIN_SYSTEM" ,"ROLE_ADMIN"})
     */
   public function bloquer (Request $request, EntityManagerInterface $manager,PartenaireRepository $partenaireRepository, UserRepository $userRepository, RoleRepository $roleRepository,$id)
   {    
        $values = json_decode($request->getContent());
        $this->tokenStorage->getToken()->getUser();
        ##### Récupération de l'ID du partenaire à bloquer #######
        $partenaire_id = $userRepository->find($id);
        ##### Récupération des utlisateurs du partenaire  #######
        $partenaire = $userRepository->findBy(array("partenaire" => $partenaire_id->getPartenaire()));
        ##### Bloquer le partenaire et es utlisateurs #######
        if($partenaire_id->getIsActive() === true){
            foreach ($partenaire as $result){
                if($result->getPartenaire()){
                    $result->setIsActive($values->isActive);
                    $manager->persist($result);
                    $manager->flush();
                }
            
            }
            $data = [
                'status' => 201,
                'message' => 'Le blocage du partenaires et ses utilisateurs a réussi avec sucess ...  ' 
            ];

            return new JsonResponse($data, 201);
        }else{
            foreach ($partenaire as $result){
                if($result->getPartenaire()){
                    $result->setIsActive($values->isActive);
                    $manager->persist($result);
                    $manager->flush();
                }
            
            }
            $data = [
                'status' => 201,
                'message' => 'Le déblocage du partenaires et ses utilisateurs a réussi avec sucess ...  ' 
            ];

            return new JsonResponse($data, 201);
        }
    }
}
