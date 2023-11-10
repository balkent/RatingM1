<?php

namespace App\DataFixtures;

use App\Entity\SupplementType;
use Exception;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SupplementTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->in(__DIR__)->files()->name('supplementTypes.json');

        if (false === $finder->hasResults()) {
            throw new Exception("Error Processing Request", 1);
        }
        foreach ($finder as $file) {
            $json = $file->getContents();
        }

        $types = json_decode($json);
        foreach ($types as $t) {
            $type = new SupplementType();
            $type->setLibelle($t->libelle);
            $type->setRating($t->rating);
            $manager->persist($type);
        }

        $manager->flush();
    }
}
