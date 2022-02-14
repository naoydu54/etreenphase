<?php

namespace App\Repository;

use App\Entity\CompanyHasAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompanyHasAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyHasAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyHasAddress[]    findAll()
 * @method CompanyHasAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyHasAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyHasAddress::class);
    }

    // /**
    //  * @return CompanyHasAddress[] Returns an array of CompanyHasAddress objects
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
    public function findOneBySomeField($value): ?CompanyHasAddress
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
