<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AffectationController extends AbstractController
{
    
    private $tokenStorage;
    public function __construct( TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/api/affectation", name="affectation", methods={"POST"})
     */
    public function affectation(PartenaireRepository $partenaireRepository, CompteRepository $compteRepository, RoleRepository $roleRepository, UserRepository $user,Request $request, EntityManagerInterface $manager)
    {
        $values = json_decode($request->getContent());
        $userConnect = $this->tokenStorage->getToken()->getUser();
        $userPartenaire = $userConnect->getPartenaire();
        $res = $user->findBy(array("partenaire" => $userPartenaire));
       
        $compteRepository->findBy(array("partenaire" => $userPartenaire));
        $dateJour = new \DateTime();
        if($dateJour >= \DateTime::createFromFormat('Y-m-d  H:i:s' , $values->dateDebut)){
            $affectation = new Affectation();
            $affectation->setDateDebut($values->dateDebut)
                        ->setDateFin($values->dateFin)
                        ->setCompte($values->compte)
                        ->setUser($values->user);
            $manager->persist($affectation);
            dd($affectation);
        }else{
            $data = [
                'status' => 500,
                'message' => 'La date est passée  . '];
    
            return new JsonResponse($data, 500);
        }
    }
}
