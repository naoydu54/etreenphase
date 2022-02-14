<?php

namespace App\Repository;

use App\Entity\TutorialAndRecipeHasProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TutorialAndRecipeHasProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method TutorialAndRecipeHasProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method TutorialAndRecipeHasProduct[]    findAll()
 * @method TutorialAndRecipeHasProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorialAndRecipeHasProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutorialAndRecipeHasProduct::class);
    }

    // /**
    //  * @return TutorialAndRecipeHasProduct[] Returns an array of TutorialAndRecipeHasProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TutorialAndRecipeHasProduct
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
