<?php

namespace App\Repository;

use App\Entity\ActualityHasProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActualityHasProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActualityHasProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActualityHasProduct[]    findAll()
 * @method ActualityHasProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualityHasProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActualityHasProduct::class);
    }

    // /**
    //  * @return ActualityHasProduct[] Returns an array of ActualityHasProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActualityHasProduct
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
