<?php

namespace App\Repository;

use App\Entity\Combination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Combination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Combination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Combination[]    findAll()
 * @method Combination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Combination::class);
    }

    // /**
    //  * @return Combination[] Returns an array of Combination objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Combination
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
