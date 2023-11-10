<?php

namespace App\DataFixtures;

use Exception;
use App\Entity\Supplement;
use App\Entity\SupplementType;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// [
//     {
//         "type": "SUPPLEMENT_TYPE",
//         "data": [
//             "first prob",
//             "...",
//         ]
//     }
// ]
class SupplementFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $finder = new Finder();
        $finder->in(__DIR__)->files()->name('supplement.json');

        if (false === $finder->hasResults()) {
            throw new Exception("Error Processing Request", 1);
        }
        foreach ($finder as $file) {
            $json = $file->getContents();
        }

        $data = json_decode($json);
        foreach ($data as $d) {
            $type = $manager
                ->getRepository(SupplementType::class)
                ->findOneBy([
                    "libelle" => $d->type,
                ]);

            foreach ($d->data as $d) {
                $supplement = new Supplement();
                $supplement->setLibelle($d);
                $supplement->setType($type);
                $manager->persist($supplement);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SupplementTypeFixtures::class,
        ];
    }
}
