<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Subject;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    /**
     * @return [] Returns an array
     */
    public function scores(): array
    {
         $conn = $this->getEntityManager()->getConnection();
         $sql = '
            SELECT 
                st.id, 
                st.last_name, 
                st.name, 
                st.email, 
                st.github,
                (
                    SELECT rating
                    FROM score as sc
                    LEFT JOIN subject su ON sc.subject_id = su.id
                    WHERE sc.student_id = st.id
                    AND su.libelle = "HTML/CSS"
                ) as "HTML/CSS", 
                ifnull(
                    (
                        SELECT sum(ifnull(sup.rating, supt.rating))
                        FROM supplement as sup
                        LEFT JOIN supplement_type supt ON supt.id = sup.type_id
                        LEFT JOIN score_supplement sco_sup ON sco_sup.supplement_id = sup.id
                        LEFT JOIN score sco ON sco.id = sco_sup.score_id
                        LEFT JOIN subject sub ON sco.subject_id = sub.id
                        WHERE sco.student_id = st.id
                        AND sub.libelle = "HTML/CSS"
                    ), 
                    0.0
                ) as "HTML/CSS SUP", 
                (
                    SELECT count(sup.id)
                    FROM supplement as sup
                    LEFT JOIN supplement_type supt ON supt.id = sup.type_id
                    LEFT JOIN score_supplement sco_sup ON sco_sup.supplement_id = sup.id
                    LEFT JOIN score sco ON sco.id = sco_sup.score_id
                    LEFT JOIN subject sub ON sco.subject_id = sub.id
                    WHERE sco.student_id = st.id
                    AND sub.libelle = "HTML/CSS"
                ) as "HTML/CSS NBSUP",
                (
                    SELECT su.maximum_rating
                    FROM subject as su
                    WHERE su.libelle = "HTML/CSS"
                ) as "HTML/CSS MAX",
                (
                    SELECT rating
                    FROM score as sc
                    LEFT JOIN subject su ON sc.subject_id = su.id
                    WHERE sc.student_id = st.id
                    AND su.libelle = "Python"
                ) as "Python",
                ifnull(
                    (
                        SELECT sum(ifnull(sup.rating, supt.rating))
                        FROM supplement as sup
                        LEFT JOIN supplement_type supt ON supt.id = sup.type_id
                        LEFT JOIN score_supplement sco_sup ON sco_sup.supplement_id = sup.id
                        LEFT JOIN score sco ON sco.id = sco_sup.score_id
                        LEFT JOIN subject sub ON sco.subject_id = sub.id
                        WHERE sco.student_id = st.id
                        AND sub.libelle = "Python"
                    ), 
                    0.0
                ) as "Python SUP", 
                (
                    SELECT su.maximum_rating
                    FROM subject as su
                    WHERE su.libelle = "Python"
                ) as "Python MAX", 
                (
                    SELECT sco.id
                    FROM score as sco
                    LEFT JOIN subject sub ON sco.subject_id = sub.id
                    WHERE sco.student_id = st.id
                    AND sub.libelle = "HTML/CSS"
                ) as "idScore"
            FROM student as st
         ';
         $resultSet = $conn->executeQuery($sql);
 
         return $resultSet->fetchAllAssociative();
    }


//     SELECT 
//     st.id, 
//     st.last_name, 
//     st.name, 
//     st.email, 
//     (
//         SELECT rating
//         FROM score as sc
//         LEFT JOIN subject su ON sc.subject_id = su.id
//         WHERE sc.student_id = st.id
//         AND su.libelle = "HTML/CSS"
//     ) as "HTML/CSS", 
//     (
//         SELECT su.maximum_rating
//         FROM subject as su
//         WHERE su.libelle = "HTML/CSS"
//     ) as "HTML/CSS MAX",
//     (
//         SELECT rating
//         FROM score as sc
//         LEFT JOIN subject su ON sc.subject_id = su.id
//         WHERE sc.student_id = st.id
//         AND su.libelle = "Python"
//     ) as "Python",
//     (
//         SELECT su.maximum_rating
//         FROM subject as su
//         WHERE su.libelle = "Python"
//     ) as "Python MAX",
//     (
//         SELECT sum(sc.rating)
//         FROM score as sc
//         WHERE sc.student_id = st.id
//     ) as "SUM",
//     (
//         SELECT sum(su.maximum_rating)
//         FROM subject as su
//     ) as "MAX",
//     (
//         SELECT 20 * (
//             SELECT sum(sc.rating)
//             FROM score as sc
//             WHERE sc.student_id = st.id
//         ) /
//         (
//             SELECT sum(su.maximum_rating)
//             FROM subject as su
//         )
//         FROM subject as su
//     ) as "20"
// FROM student as st

    /**
     * @return [] Returns an array
     */
    public function globalScore(Student $student)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT 
                (
                    SELECT sum(sc.rating)
                    FROM score as sc
                    LEFT JOIN subject su ON sc.subject_id = su.id
                    WHERE sc.student_id = st.id
                ) as "rating",
                ifnull(
                    (
                        SELECT sum(ifnull(sup.rating, supt.rating))
                        FROM supplement as sup
                        LEFT JOIN supplement_type supt ON supt.id = sup.type_id
                        LEFT JOIN score_supplement sco_sup ON sco_sup.supplement_id = sup.id
                        LEFT JOIN score sco ON sco.id = sco_sup.score_id
                        WHERE sco.student_id = st.id
                    ), 
                    0.0
                ) as "ratingUp",
                (
                    SELECT sum(su.maximum_rating)
                    FROM subject as su
                ) as "ratingMax"
            FROM student as st
            WHERE st.id = :id
        ';

        $resultSet = $conn->executeQuery($sql, ['id' => $student->getId()]);

        return $resultSet->fetchAssociative();
    }

    /**
     * @return [] Returns an array
     */
    public function scoreByStudentAndSubject(Student $student, Subject $subject): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT 
                (
                    SELECT rating
                    FROM score as sc
                    LEFT JOIN subject su ON sc.subject_id = su.id
                    WHERE sc.student_id = st.id
                    AND su.libelle = :subject
                ) as "rating", 
                ifnull(
                    (
                        SELECT sum(ifnull(sup.rating, supt.rating))
                        FROM supplement as sup
                        LEFT JOIN supplement_type supt ON supt.id = sup.type_id
                        LEFT JOIN score_supplement sco_sup ON sco_sup.supplement_id = sup.id
                        LEFT JOIN score sco ON sco.id = sco_sup.score_id
                        LEFT JOIN subject sub ON sco.subject_id = sub.id
                        WHERE sco.student_id = st.id
                        AND sub.libelle = :subject
                    ), 
                    0.0
                ) as "ratingUp",
                (
                    SELECT su.maximum_rating
                    FROM subject as su
                    WHERE su.libelle = :subject
                ) as "ratingMax"
            FROM student as st
            WHERE st.id = :id
        ';

        $resultSet = $conn->executeQuery($sql, [
            'subject' => $subject->getLibelle(),
            'id' => $student->getId()
        ]);

        return $resultSet->fetchAssociative();
    }
}
