<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciceController extends AbstractController
{
    #[Route('/exercice/{id}', name: 'app_exercice_show')]
    public function show(Exercice $exercice, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer un nouveau commentaire
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setExercice($exercice);
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_show', ['id' => $exercice->getId()]);
        }

        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
            'chapter' => $exercice->getChapter(),
            'subject' => $exercice->getChapter()->getSubject(),
            'comments' => $exercice->getComments(),
            'commentForm' => $form->createView(),
        ]);
    }
} 