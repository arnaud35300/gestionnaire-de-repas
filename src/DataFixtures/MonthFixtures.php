<?php

namespace App\DataFixtures;

use App\Entity\Month;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MonthFixtures extends Fixture implements FixtureGroupInterface
{
    const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function load(ObjectManager $manager)
    {
        foreach (self::MONTHS as $value) {

            $month = new Month();
            $month
                ->setName($value)
                ->setCreatedAt(new \DateTime());

            $manager->persist($month);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['date-group'];
    }
}
