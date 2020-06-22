<?php

namespace App\DataFixtures;

use App\Entity\Moment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MomentFixtures extends Fixture implements FixtureGroupInterface
{
    const MOMENT = ['Breakfast', 'Lunch', 'Dinner'];

    public function load(ObjectManager $manager)
    {
        foreach (self::MOMENT as $value) {

            $moment = new Moment();
            $moment
                ->setName($value)
                ->setCreatedAt(new \DateTime());

            $manager->persist($moment);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['meal-group'];
    }
}
