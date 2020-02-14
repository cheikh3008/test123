<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)

    {   #### Ajout role admin systeme ######
        $role_super_admin = new Role();
        $role_super_admin->setLibelle("ROLE_SUPER_ADMIN");
        $manager->persist($role_super_admin);

        #### Ajout role admin  ######
        $role_admin = new Role();
        $role_admin->setLibelle("ROLE_ADMIN");
        $manager->persist($role_admin);

        #### Ajout role caissier  ######
        $role_caissier = new Role();
        $role_caissier->setLibelle("ROLE_CAISSIER");
        $manager->persist($role_caissier);

        #### Ajout role apartenaire ######
        $role_partenaire = new Role();
        $role_partenaire->setLibelle("ROLE_PARTENAIRE");
        $manager->persist($role_partenaire);
        
        #### Ajout role admin apartenaire ######
        $role_admin_partenaire = new Role();
        $role_admin_partenaire->setLibelle("ROLE_ADMIN_PARTENAIRE");
        $manager->persist($role_admin_partenaire);

        #### Ajout role apartenaire ######
        $role_user_partenaire = new Role();
        $role_user_partenaire->setLibelle("ROLE_USER_PARTENAIRE");
        $manager->persist($role_user_partenaire);
        
        #### Ajout un admin system ######
        $user = new User();
        $user->setEmail("cheikh3008@gmail.com")
            ->setRole($role_super_admin)
            ->setPassword($this->encoder->encodePassword( $user , "admin123"))
            ->setPrenom("Cheikh")
            ->setNom("Dieng");
        $manager->persist($user);
        $manager->flush();
    }
}
