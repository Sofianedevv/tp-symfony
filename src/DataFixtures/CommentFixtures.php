<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Exercice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($subjectIndex = 0; $subjectIndex < 5; $subjectIndex++) {
            for ($chapterIndex = 0; $chapterIndex < 4; $chapterIndex++) {
                for ($exerciceIndex = 1; $exerciceIndex <= 3; $exerciceIndex++) {
                    $exerciceRef = 'exercice_'.$subjectIndex.'_'.$chapterIndex.'_'.$exerciceIndex;
                    
                    try {
                        $exercice = $this->getReference($exerciceRef, Exercice::class);
                        
                        for ($i = 1; $i <= 2; $i++) {
                            $comment = new Comment();
                            $comment->setContent("Commentaire $i sur l'exercice " . $exerciceIndex);
                            $comment->setExercice($exercice);
                            
                            $userIndex = rand(1, 10);
                            $user = $this->getReference('user_' . $userIndex, User::class);
                            $comment->setUser($user);
                            
                            $manager->persist($comment);
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ExerciceFixtures::class,
        ];
    }
} 