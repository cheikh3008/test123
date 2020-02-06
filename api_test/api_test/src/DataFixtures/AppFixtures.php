<?php

namespace App\DataFixtures;

use App\Entity\Services;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $nomService = 'Cardiologie';
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 5; $i++){
            $Service = new Services();
            $Service->setNomService($this->nomService);
            $manager->persist($Service);
        }

        $manager->flush();
    }
}
