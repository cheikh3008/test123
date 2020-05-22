<?php

namespace App\Repository;

use App\Entity\Depot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Depot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depot[]    findAll()
 * @method Depot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depot::class);
    }
    public function findDepotByPartenaire($id)
    {
        return $this->getEntityManager()
                ->createQuery('SELECT D.id, C.numCompte , D.montant, D.createdAt FROM App\Entity\Depot D,App\Entity\Compte C, App\Entity\Partenaire P WHERE C.id = D.compte AND P.id = C.partenaire AND P.id = '.$id
        )->getResult();
    }
    
    public function findAllDepot()
    {
        return $this->getEntityManager()
                ->createQuery('SELECT DISTINCT D.id, C.numCompte , D.montant, D.createdAt FROM App\Entity\Depot D,App\Entity\Compte C WHERE C.id = D.compte ' 
        )->getResult();
    }
}

    // /**
    //  * @return Depot[] Returns an array of Depot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Depot
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

