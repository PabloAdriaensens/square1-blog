<?php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Crear el usuario admin
        $admin = new User();
        $admin->setEmail('admin@square1.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Crear el usuario user
        $user = new User();
        $user->setEmail('user@square1.com');
        $user->setPassword($this->passwordEncoder->hashPassword($user, 'user123'));
        $manager->persist($user);

        $manager->flush();
    }
}
