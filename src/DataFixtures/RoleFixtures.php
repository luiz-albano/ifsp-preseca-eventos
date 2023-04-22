<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Role;
use Doctrine\DBAL\Connection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $conn = $manager->getConnection();
        $conn->exec("ALTER TABLE role AUTO_INCREMENT = 1");

        $roles = [
            'ROLE_ADMIN' => 'Administradores de eventos',
            'ROLE_ASSISTANT' => 'Assistentes de eventos',
            'ROLE_TEACHER' => 'Professores',
            'ROLE_STUDENT' => 'Alunos',
            'ROLE_USER' => 'Usuários'
        ];

        foreach( $roles as $slug => $label ) {
            $role = new Role();
            $role->setLabel($label);
            $role->setSlug($slug);
            $role->setCreatedAt( new \DateTimeImmutable("2023-04-02 17:11:00"), new \DateTimeZone('America/Sao_Paulo') );
            $role->setUpdatedAt( new DateTime("2023-04-02 17:11:00"), new \DateTimeZone('America/Sao_Paulo') );

            $manager->persist($role);
        }

        $manager->flush();
    }
}
