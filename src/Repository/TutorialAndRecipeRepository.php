<?php

namespace App\Repository;

use App\Entity\TutorialAndRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TutorialAndRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method TutorialAndRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method TutorialAndRecipe[]    findAll()
 * @method TutorialAndRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutorialAndRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutorialAndRecipe::class);
    }

    // /**
    //  * @return TutorialAndRecipe[] Returns an array of TutorialAndRecipe objects
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
    public function findOneBySomeField($value): ?TutorialAndRecipe
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
