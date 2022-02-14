<?php

namespace App\Repository;

use App\Entity\CompanyHasMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyHasMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyHasMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyHasMenu[]    findAll()
 * @method CompanyHasMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyHasMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyHasMenu::class);
    }

    // /**
    //  * @return CompanyHasMenu[] Returns an array of CompanyHasMenu objects
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
    public function findOneBySomeField($value): ?CompanyHasMenu
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
