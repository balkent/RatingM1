<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Exception;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

// [
//     {
//         "libelle": "HTML/CSS",
//         "maximumRating": 35
//     },
//     {
//         "libelle": "Python",
//         "maximumRating": 40
//     }
// ]

class SubjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->in(__DIR__)->files()->name('subjectsData.json');

        if (false === $finder->hasResults()) {
            throw new Exception("Error Processing Request", 1);
        }

        foreach ($finder as $file) {
            $json = $file->getContents();
        }

        $subjects = json_decode($json);
        foreach ($subjects as $stud) {
            $subject = new Subject();
            $subject->setLibelle($stud->libelle);
            $subject->setMaximumRating($stud->maximumRating);
            $manager->persist($subject);
        }

        $manager->flush();
    }
}
