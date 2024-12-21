<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubjectController extends AbstractController
{
    #[Route('/subject/{id}', name: 'app_subject_show')]
    public function show(Subject $subject): Response
    {
        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'chapters' => $subject->getChapters(),
        ]);
    }
} 