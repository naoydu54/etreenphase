<?php

namespace App\Repository;

use App\Entity\ProductHasAttributeItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductHasAttributeItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductHasAttributeItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductHasAttributeItem[]    findAll()
 * @method ProductHasAttributeItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductHasAttributeItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductHasAttributeItem::class);
    }

    // /**
    //  * @return ProductHasAttributeItem[] Returns an array of ProductHasAttributeItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductHasAttributeItem
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
