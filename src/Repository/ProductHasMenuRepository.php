<?php

namespace App\Repository;

use App\Entity\ProductHasMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductHasMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductHasMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductHasMenu[]    findAll()
 * @method ProductHasMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductHasMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductHasMenu::class);
    }

    // /**
    //  * @return ProductHasMenu[] Returns an array of ProductHasMenu objects
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
    public function findOneBySomeField($value): ?ProductHasMenu
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
