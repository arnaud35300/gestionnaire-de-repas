<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    // object references
    public const ADMIN = 'admin';
    public const USER = 'user';

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setName('user')
            ->setEmail('user@user.com')
            ->setPath('user_profile.svg')
            ->setPassword(
                $this->encoder->encodePassword($user, 'password')
            )
            ->setRole($this->getReference(RoleFixtures::USER_ROLE_REFERENCE))
            ->setCreatedAt(new \DateTime());

        $admin = new User();
        $admin->setName('admin')
            ->setEmail('admin@admin.com')
            ->setPath('user_profile.svg')
            ->setPassword(
                $this->encoder->encodePassword($admin, 'password')
            )
            ->setRole($this->getReference(RoleFixtures::ADMIN_ROLE_REFERENCE))
            ->setCreatedAt(new \DateTime());

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();

        $this->addReference(self::ADMIN, $admin);
        $this->addReference(self::USER, $user);
    }

    public function getDependencies()
    {
        // load role fixtures before self
        return array(
            RoleFixtures::class
        );
    }

    public static function getGroups(): array
    {
        return ['user-group'];
    }
}
