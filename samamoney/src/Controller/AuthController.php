<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthController extends AbstractController
{
    // private $passwordEncoder;
    // public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    // {
    //     $this->passwordEncoder= $passwordEncoder;
    // }
    public function register(Request $request, UserPasswordEncoderInterface $encoder,EntityManagerInterface $em)
    {
        $value = json_encode($request->getContent());
        $user = new User();
        // $em = $this->getDoctrine()->getManager();
        if(isset($value->username,$value->password)){
            
            $user->setEmail($value->username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $value->password));
            $repo = $this->getDoctrine()->getRepository(Role::class);
            $role = $repo->find($value->libelle);
            $user->setRole($role);
            $role  = [];
            if($role->getLibelle() == "admin"){
                $role =  (["ROLE_ADMIN"]);
            }elseif($role->getLibelle()=="caissier"){
                $role =  (["ROLE_CAISSIER"]);
            }else{
                $role =  (["ROLE_ADMIN_SYSTEM"]);
            }
            $user->setRoles($role);
            
            $em->persist($user);
            $em->flush();
        }
        
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }
    
}
