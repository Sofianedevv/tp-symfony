<?php

namespace App\DataFixtures;

use App\Entity\Chapter;
use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChapterFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $chaptersData = [
            'Mathématiques' => ['Algèbre', 'Géométrie', 'Analyse', 'Probabilités'],
            'Physique' => ['Mécanique', 'Électricité', 'Optique', 'Thermodynamique'],
            'Chimie' => ['Chimie organique', 'Chimie inorganique', 'Thermochimie'],
            'Informatique' => ['Algorithmes', 'Bases de données', 'Programmation orientée objet', 'Web'],
            'Biologie' => ['Cellule', 'Génétique', 'Écologie', 'Évolution']
        ];

        $subjects = ['Mathématiques', 'Physique', 'Chimie', 'Informatique', 'Biologie'];

        foreach ($subjects as $subjectIndex => $subjectName) {
            if (isset($chaptersData[$subjectName])) {
                foreach ($chaptersData[$subjectName] as $chapterKey => $chapterTitle) {
                    $chapter = new Chapter();
                    $chapter->setTitle($chapterTitle);
                    
                    // Récupérer la référence du sujet
                    $subject = $this->getReference('subject_'.$subjectIndex, Subject::class);
                    $chapter->setSubject($subject);
                    
                    $manager->persist($chapter);
                    // Sauvegarder la référence du chapitre
                    $this->addReference('chapter_'.$subjectIndex.'_'.$chapterKey, $chapter);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SubjectFixtures::class,
        ];
    }
} 