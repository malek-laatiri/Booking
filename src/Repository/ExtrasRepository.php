<?php

namespace App\Repository;

use App\Entity\Extras;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Extras|null find($id, $lockMode = null, $lockVersion = null)
 * @method Extras|null findOneBy(array $criteria, array $orderBy = null)
 * @method Extras[]    findAll()
 * @method Extras[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtrasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Extras::class);
    }

    // /**
    //  * @return Extras[] Returns an array of Extras objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Extras
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
