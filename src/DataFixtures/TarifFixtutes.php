<?php

namespace App\DataFixtures;

use App\Entity\Tarif;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TarifFixtutes extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tarif = new Tarif();
        $tarif->setBorneInf(500)
            ->setBorneSup(1500)
            ->setFrais(100);
        $manager->persist($tarif);
     /**
      * ---------------------------------------------------------------------------
      */
        $tarif = new Tarif();
        $tarif->setBorneInf(1501)
            ->setBorneSup(3000)
            ->setFrais(200);
        $manager->persist($tarif);
        /**
      * ---------------------------------------------------------------------------
      */
        $tarif = new Tarif();
        $tarif->setBorneInf(3001)
            ->setBorneSup(5000)
            ->setFrais(400);
        $manager->persist($tarif);
        /**
      * ---------------------------------------------------------------------------
      */
        $tarif = new Tarif();
        $tarif->setBorneInf(5001)
            ->setBorneSup(10000)
            ->setFrais(600);
        $manager->persist($tarif);
        /**
      * ---------------------------------------------------------------------------
      */
        $tarif = new Tarif();
        $tarif->setBorneInf(10001)
            ->setBorneSup(15000)
            ->setFrais(1000);
        $manager->persist($tarif);
        /**
      * ---------------------------------------------------------------------------
      */
        $tarif = new Tarif();
        $tarif->setBorneInf(15001)
            ->setBorneSup(20000)
            ->setFrais(1200);
        $manager->persist($tarif);
        /**
      * ---------------------------------------------------------------------------
      */
        $tarif = new Tarif();
        $tarif->setBorneInf(20001)
            ->setBorneSup(25000)
            ->setFrais(1400);
        $manager->persist($tarif);
          /**
      * ---------------------------------------------------------------------------
      */
      $tarif = new Tarif();
      $tarif->setBorneInf(25001)
          ->setBorneSup(30000)
          ->setFrais(1600);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(30001)
          ->setBorneSup(50000)
          ->setFrais(1800);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(50001)
          ->setBorneSup(60000)
          ->setFrais(2300);
      $manager->persist($tarif);
    /**
      * ---------------------------------------------------------------------------
      */
      $tarif = new Tarif();
      $tarif->setBorneInf(60000)
          ->setBorneSup(75000)
          ->setFrais(2700);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(75001)
          ->setBorneSup(100000)
          ->setFrais(3200);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(1000001)
          ->setBorneSup(125000)
          ->setFrais(3600);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(125001)
          ->setBorneSup(150000)
          ->setFrais(4000);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(150001)
          ->setBorneSup(200000)
          ->setFrais(4800);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(200001)
          ->setBorneSup(250000)
          ->setFrais(6350);
      $manager->persist($tarif);
        /**
    * ---------------------------------------------------------------------------
    */
    $tarif = new Tarif();
    $tarif->setBorneInf(250001)
        ->setBorneSup(300000)
        ->setFrais(8050);
    $manager->persist($tarif);
    /**
  * ---------------------------------------------------------------------------
  */
    $tarif = new Tarif();
    $tarif->setBorneInf(300001)
        ->setBorneSup(400000)
        ->setFrais(9750);
    $manager->persist($tarif);
    /**
  * ---------------------------------------------------------------------------
  */
    $tarif = new Tarif();
    $tarif->setBorneInf(400001)
        ->setBorneSup(600000)
        ->setFrais(11850);
    $manager->persist($tarif);
    
    
     /**
      * ---------------------------------------------------------------------------
      */
      $tarif = new Tarif();
      $tarif->setBorneInf(600001)
          ->setBorneSup(750000)
          ->setFrais(13550);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(750001)
          ->setBorneSup(1000000)
          ->setFrais(21650);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(1000001)
          ->setBorneSup(1250000)
          ->setFrais(24200);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(1250001)
          ->setBorneSup(1500000)
          ->setFrais(31850);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(1500001)
          ->setBorneSup(2000000)
          ->setFrais(35650);
      $manager->persist($tarif);
      /**
    * ---------------------------------------------------------------------------
    */
      $tarif = new Tarif();
      $tarif->setBorneInf(2000001)
          ->setBorneSup(3000000)
          ->setFrais(0.02);
      $manager->persist($tarif);
        /**
    * ---------------------------------------------------------------------------
    */
    
    $manager->flush();
    
    }
}
