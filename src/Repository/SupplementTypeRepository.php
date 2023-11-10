<?php

namespace App\Repository;

use App\Entity\SupplementType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

//    /**
//     * @return SupplementType[] Returns an array of SupplementType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

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
