<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted({"ROLE_PARTENAIRE" ,"ROLE_ADMIN_PARTENAIRE"})
     */
    public function affectation(PartenaireRepository $partenaireRepository, CompteRepository $compteRepository, UserRepository $user,Request $request, EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $values = json_decode($request->getContent());
        $userConnect = $this->tokenStorage->getToken()->getUser();
        $userPartenaire = $userConnect->getPartenaire();
        $affectation = $serializer->deserialize($request->getContent(), Affectation::class, 'json');

        $dateJour  = new \DateTime();
         ##### Transforme un texte anglais en timestamp  ######
        $dateDebut = strtotime ($values->dateDebut);
        $dateFin = strtotime($values->dateFin);
        $dateJour = strtotime($dateJour->format('Y-m-d'));

        ##### Vérifie si la date est passée ######
        
        if($dateDebut < $dateJour )
        {
            $data = [
                'status' => 500,
                'message' => 'Impossible d\'affecter à une date pasée . '
                ] ;
            return new JsonResponse($data, 500);
        }
        
         ##### Vérifie si la date de fin supérieur à la date de début ######
        if($dateFin < $dateDebut )
        {
            $data = [
                'status' => 500,
                'message' => 'La date de fin doit être doit supérieur à la date de début . '
                ] ;
            return new JsonResponse($data, 500);
        }

        ##### Vérifie si le compte appartient à ce partenaire ######
        if($affectation->getCompte()->getPartenaire() != $userPartenaire)
        {
            $data = [
                'status' => 500,
                'message' => 'Impossible d\'affecter à un compte qui ne vous appartient pas . '
                ] ;

            return new JsonResponse($data, 500);
         
        }
        ##### Vérifie si l'utlisateur appartient à ce partenaire ######
        if($affectation->getUser()->getPartenaire() != $userPartenaire)
        {
            $data = [
                'status' => 500,
                'message' => 'Cet utilisateur n\'appartient à ce partenaire. '
                ] ;

            return new JsonResponse($data, 500);
         
        }
        ##### Vérifie si l'utilisateur a le role USER_PARTENAIRE ######
        if($affectation->getUser()->getRole()->getLibelle() === "ROLE_USER_PARTENAIRE")
        {
           
            $affectation->setDateDebut(\DateTime::createFromFormat('Y-m-d', $values->dateDebut))
                        ->setDateFin(\DateTime::createFromFormat('Y-m-d', $values->dateFin))
                        ->setCompte($affectation->getCompte())
                        ->setUser($affectation->getUser());
            $manager->persist($affectation);
            $manager->flush();
            $data = [
                'status' => 201,
                'message' => 'L\'affectation a réussi avec sucess... '
                ] ;

            return new JsonResponse($data, 201);
        }
        else
        {
            $data = [
                'status' => 500,
                'message' => 'L\'affectation n\'est autorisé qu\'aux users partenaire... '
                ] ;

            return new JsonResponse($data, 500);
        }
    }
}
