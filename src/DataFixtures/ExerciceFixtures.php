<?php

namespace App\DataFixtures;

use App\Entity\Exercice;
use App\Entity\Chapter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExerciceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($subjectIndex = 0; $subjectIndex < 5; $subjectIndex++) {
            for ($chapterIndex = 0; $chapterIndex < 4; $chapterIndex++) {
                $refName = 'chapter_'.$subjectIndex.'_'.$chapterIndex;
                
                try {
                    $chapter = $this->getReference($refName, Chapter::class);
                    
                    for ($i = 1; $i <= 3; $i++) {
                        $exercice = new Exercice();
                        $exercice->setTitle("Exercice $i");
                        $exercice->setContent("Contenu détaillé de l'exercice $i du chapitre " . $chapter->getTitle());
                        $exercice->setChapter($chapter);
                        
                        $manager->persist($exercice);
                        $this->addReference('exercice_'.$subjectIndex.'_'.$chapterIndex.'_'.$i, $exercice);
                    }
                } catch (\Exception $e) {
                    // Skip if reference doesn't exist
                    continue;
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ChapterFixtures::class,
        ];
    }
} 