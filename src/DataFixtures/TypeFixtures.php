<?php
namespace App\DataFixtures;

use App\Entity\Month;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TypeFixtures extends Fixture implements FixtureGroupInterface
{
    const TYPE = ['Healthy', 'Neutral', 'Junk food', 'Gastronomy', 'Fat'];

    public function load(ObjectManager $manager)
    {
        foreach (self::TYPE as $value) {

            $type = new Type();
            $type
                ->setName($value)
                ->setCreatedAt(new \DateTime());

            $manager->persist($type);
        }
        
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['meal-group'];
    }
}
