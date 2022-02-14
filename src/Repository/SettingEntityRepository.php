<?php

namespace App\Repository;

use App\Entity\SettingEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SettingEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SettingEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SettingEntity[]    findAll()
 * @method SettingEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SettingEntity::class);
    }

    // /**
    //  * @return SettingEntity[] Returns an array of SettingEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SettingEntity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
