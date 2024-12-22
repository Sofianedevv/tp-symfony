<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoriteController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorites')]
    public function index(): Response
    {
        return $this->render('favorite/index.html.twig', [
            'favorites' => $this->getUser()->getFavorites()
        ]);
    }

    #[Route('/favorite/toggle/{id}', name: 'app_favorite_toggle', methods: ['POST'])]
    public function toggle(Subject $subject, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], 403);
        }

        $isFavorite = $user->isFavorite($subject);

        if ($isFavorite) {
            $user->removeFavorite($subject);
            $message = 'Retiré des favoris';
        } else {
            $user->addFavorite($subject);
            $message = 'Ajouté aux favoris';
        }

        $entityManager->flush();

        return $this->json([
            'success' => true,
            'isFavorite' => !$isFavorite,
            'message' => $message
        ]);
    }
} 