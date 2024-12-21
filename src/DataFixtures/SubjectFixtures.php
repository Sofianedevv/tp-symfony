<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $subjects = [
            'Mathématiques',
            'Physique',
            'Chimie',
            'Informatique',
            'Biologie'
        ];

        foreach ($subjects as $key => $subjectName) {
            $subject = new Subject();
            $subject->setName($subjectName);
            $manager->persist($subject);
            $this->addReference('subject_' . $key, $subject);
        }

        $manager->flush();
    }
} 