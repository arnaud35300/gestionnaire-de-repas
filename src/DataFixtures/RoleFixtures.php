<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements FixtureGroupInterface
{
    // object references
    public const ADMIN_ROLE_REFERENCE = 'admin-role';
    public const USER_ROLE_REFERENCE = 'user-role';

    public function load(ObjectManager $manager)
    {
        $adminRole = new Role();
        $adminRole
            ->setName('ROLE_ADMINISTRATOR')
            ->setCreatedAt(new \DateTime());

        $userRole = new Role();
        $userRole
            ->setName('ROLE_USER')
            ->setCreatedAt(new \DateTime());

        $manager->persist($adminRole);
        $manager->persist($userRole);
        $manager->flush();

        $this->addReference(self::ADMIN_ROLE_REFERENCE, $adminRole);
        $this->addReference(self::USER_ROLE_REFERENCE, $userRole);
    }

    public static function getGroups(): array
    {
        return ['role-group'];
    }
}
