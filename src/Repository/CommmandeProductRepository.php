<?php

namespace App\Repository;

use App\Entity\CommmandeProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommmandeProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommmandeProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommmandeProduct[]    findAll()
 * @method CommmandeProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommmandeProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommmandeProduct::class);
    }

    // /**
    //  * @return CommmandeProduct[] Returns an array of CommmandeProduct objects
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
    public function findOneBySomeField($value): ?CommmandeProduct
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
