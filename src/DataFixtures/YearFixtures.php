<?php

namespace App\DataFixtures;

use App\Entity\Year;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class YearFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager)
    {
        $year = new Year();
        $year
            ->setName(
                (int) (new \DateTime)->format('Y')
            )
            ->setCreatedAt(new \DateTime());

        $manager->persist($year);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['date-group'];
    }
}
