<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Subject;
use App\Entity\Chapter;
use App\Entity\Exercice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création de l'administrateur
        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Création d'un utilisateur normal
        $user = new User();
        $user->setFirstName('User');
        $user->setLastName('Test');
        $user->setEmail('user@test.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $manager->persist($user);

        // Création des matières
        $subjects = [];
        $subjectNames = ['Mathématiques', 'Physique', 'Chimie', 'Informatique', 'Biologie', 'Anglais'];
        
        foreach ($subjectNames as $name) {
            $subject = new Subject();
            $subject->setName($name);
            $manager->persist($subject);
            $subjects[] = $subject;
        }

        // Création des chapitres et exercices
        foreach ($subjects as $subject) {
            for ($i = 1; $i <= 3; $i++) {
                $chapter = new Chapter();
                $chapter->setTitle("Chapitre $i - " . $subject->getName());
                $chapter->setSubject($subject);
                $manager->persist($chapter);

                // Création des exercices pour chaque chapitre
                for ($j = 1; $j <= 3; $j++) {
                    $exercice = new Exercice();
                    $exercice->setTitle("Exercice $j du Chapitre $i");
                    $exercice->setContent("Contenu de l'exercice $j du chapitre $i de " . $subject->getName());
                    $exercice->setChapter($chapter);
                    $manager->persist($exercice);
                }
            }
        }

        // Ajouter quelques favoris pour l'utilisateur test
        $user->addFavorite($subjects[0]); // Ajoute les maths aux favoris
        $user->addFavorite($subjects[1]); // Ajoute la physique aux favoris

        $manager->flush();
    }
}
