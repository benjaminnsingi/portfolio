<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();

        $admin
            ->setLastname('Doe')
            ->setFirstname('John')
            ->setEmail('john.doe@portfolio.com')
            ->setPassword(
                $this->passwordEncoder->encodePassword($admin, 'user123!')
            )
            ->setRoles(User::ROLE_ADMIN);

        $manager->persist($admin);
        $manager->flush();
    }
}
