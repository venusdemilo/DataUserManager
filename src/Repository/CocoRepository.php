<?php

namespace App\Repository;

use App\Entity\Coco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Coco|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coco|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coco[]    findAll()
 * @method Coco[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Coco::class);
    }

    // /**
    //  * @return Coco[] Returns an array of Coco objects
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
    public function findOneBySomeField($value): ?Coco
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
