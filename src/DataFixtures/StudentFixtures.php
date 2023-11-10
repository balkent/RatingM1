<?php

namespace App\DataFixtures;

use Exception;
use App\Entity\Student;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

// [
//     {
//         "lastName": "bob",
//         "name": "morane",
//         "email": "bob.morane@mail.com",
//         "github": "bmor",
//         "scores":[
//             {
//                 "libelle": "HTML/CSS",
//                 "rating": 25.68  
//             },
//             {
//                 "libelle": "Python",
//                 "rating": 25.00
//             }
//         ]
//     },
// ]

class StudentFixtures extends Fixture
{
    public const STUDENTS_REFERENCE = 'students';

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
            $student = new Student();
            $student->setLastName($stud->lastName);
            $student->setName($stud->name);
            $student->setEmail($stud->email);
            $manager->persist($student);
        }

        $manager->flush();
    }
}
