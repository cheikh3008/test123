<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PartenaireRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     */
   public function bloquer (Request $request, EntityManagerInterface $manager,PartenaireRepository $partenaireRepository, UserRepository $userRepository, RoleRepository $roleRepository,$id)
   {
        
        $partenaire = $userRepository->find($id);
        $res = $userRepository->findBy(array("partenaire" => $partenaire->getPartenaire()));
        $count = count($res);
        for ($i = 0; $i < $count; $i++){
            if($partenaire->getPartenaire()){
                $partenaire->setIsActive(false);
                $manager->persist($partenaire);
                $manager->flush();
            }
        
        }
        
            return new JsonResponse("ok");
         
   }
}
