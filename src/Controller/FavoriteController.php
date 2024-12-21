<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;

#[IsGranted('ROLE_USER')]
class FavoriteController extends AbstractController
{
    #[Route('/favorite/{id}/toggle', name: 'app_favorite_toggle')]
    public function toggle(Subject $subject, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $wasFavorite = $user->isFavorite($subject);

        try {
            if ($wasFavorite) {
                $user->removeFavorite($subject);
                $message = 'Retiré des favoris';
            } else {
                $user->addFavorite($subject);
                $message = 'Ajouté aux favoris';
            }

            $em->persist($user);
            $em->flush();
            $em->refresh($user); // Rafraîchit l'entité depuis la base de données

            // Vérifier l'état après la mise à jour
            $isFavorite = $user->isFavorite($subject);

            return $this->json([
                'success' => true,
                'isFavorite' => $isFavorite,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Une erreur est survenue'
            ], 500);
        }
    }

    #[Route('/favorites', name: 'app_favorites')]
    public function index(): Response
    {
        return $this->render('favorite/index.html.twig', [
            'favorites' => $this->getUser()->getFavorites()
        ]);
    }
} 