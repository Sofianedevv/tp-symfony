<?php

namespace App\Controller;

use App\Entity\Chapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChapterController extends AbstractController
{
    #[Route('/chapter/{id}', name: 'app_chapter_show')]
    public function show(Chapter $chapter): Response
    {
        return $this->render('chapter/show.html.twig', [
            'chapter' => $chapter,
            'subject' => $chapter->getSubject(),
            'exercises' => $chapter->getExercises(),
        ]);
    }
} 