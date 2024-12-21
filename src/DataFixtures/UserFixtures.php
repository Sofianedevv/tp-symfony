<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs normaux
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName("Prénom{$i}");
            $user->setLastName("Nom{$i}");
            
            // Hash du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        // Création de l'admin
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'jesuisadmin');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        // Création de l'utilisateur banni
        $bannedUser = new User();
        $bannedUser->setEmail('banned@example.com');
        $bannedUser->setRoles(['ROLE_BANNED']);
        $bannedUser->setFirstName('Banned');
        $bannedUser->setLastName('User');
        $hashedPassword = $this->passwordHasher->hashPassword($bannedUser, 'bannis');
        $bannedUser->setPassword($hashedPassword);
        $manager->persist($bannedUser);

        $manager->flush();
    }
} 