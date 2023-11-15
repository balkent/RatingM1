<?php

namespace App\Repository;

use App\Entity\SupplementType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SupplementType>
 *
 * @method SupplementType|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupplementType|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupplementType[]    findAll()
 * @method SupplementType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplementTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupplementType::class);
    }

//    public function findOneBySomeField($value): ?SupplementType
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
