<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\Supplement;
use App\Entity\SupplementType;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Supplement>
 *
 * @method Supplement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supplement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supplement[]    findAll()
 * @method Supplement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupplementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supplement::class);
    }

    /**
     * @return SupplementType[] Returns an array of SupplementType objects
     */
    public function findByStudentAndSubject(       
        Student $student,
        Subject $subject
    ): array {
        $types = $this
            ->getEntityManager()
            ->getRepository(SupplementType::class)
            ->findAll();
        $tab = [];
        foreach ($types as $type) {
            $supplements = $this->findByTypeAndStudentAndSubject(
                $type,
                $student,
                $subject
            );
            if (0 < count($supplements)) {
                $tab[$type->getLibelle()] = $supplements;
            }
        }

        return $tab;
    }

    public function findByTypeAndStudentAndSubject(
        SupplementType $type,
        Student $student,
        Subject $subject
    ): array {
        return $this
            ->createQueryBuilder('sup')
            ->leftJoin('sup.type', 'supt')
            ->leftJoin('sup.scores', 'sco')
            ->leftJoin('sco.subject', 'sub')
            ->andWhere('supt.libelle = :type')
            ->andWhere('sco.student = :student')
            ->andWhere('sub.libelle = :subject')
            ->setParameters([
                'type' => $type->getLibelle(),
                'student' => $student,
                'subject' => $subject->getLibelle(),
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
