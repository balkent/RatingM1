<?php

namespace App\DataFixtures;

use Exception;
use App\Entity\Score;
use App\Entity\Student;
use App\Entity\Subject;
use Symfony\Component\Finder\Finder;
use App\DataFixtures\StudentFixtures;
use App\DataFixtures\SubjectFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ScoreFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->in(__DIR__)->files()->name('studentsData.json');

        if (false === $finder->hasResults()) {
            throw new Exception("Error Processing Request", 1);
        }
        foreach ($finder as $file) {
            $json = $file->getContents();
        }
        $students = json_decode($json);
        foreach ($students as $stud) {
            $student = $manager
                ->getRepository(Student::class)
                ->findOneBy([
                    "email" => $stud->email,
                ]);
            foreach ($stud->scores as $note) {
                $subject = $manager
                    ->getRepository(Subject::class)
                    ->findOneBy([
                        "libelle" => $note->libelle,
                    ]);
                $score = new Score();
                $score->setStudent($student);
                $score->setSubject($subject);
                $score->setRating($note->rating);
                $manager->persist($score);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            StudentFixtures::class,
            SubjectFixtures::class,
        ];
    }
}
