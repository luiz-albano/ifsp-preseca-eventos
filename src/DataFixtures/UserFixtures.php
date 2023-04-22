<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $conn = $manager->getConnection();
        $conn->exec("ALTER TABLE user AUTO_INCREMENT = 1");

        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
        ]);
        $passwordHasher = new UserPasswordHasher($passwordHasherFactory);

        $user = new User();
        $user->setEmail('contato@luizalbano.com.br');
        $user->setUsername('administrador');
        $hashedPassword = $passwordHasher->hashPassword($user, 'ufabc202304');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);

        $role = $manager->getRepository(Role::class)->findOneBy(['slug' => 'ROLE_ADMIN']);
        $user->addRolesDb($role);

        $manager->persist($user);
        $manager->flush();
    }
}
