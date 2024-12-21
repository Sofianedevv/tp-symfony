<?php

namespace App\Controller;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
    public function index(): Response
    {
        return $this->render('notification/index.html.twig', [
            'notifications' => $this->getUser()->getNotifications(),
        ]);
    }

    #[Route('/notification/{id}/mark-as-read', name: 'app_notification_mark_read')]
    public function markAsRead(Notification $notification, EntityManagerInterface $entityManager): Response
    {
        if ($notification->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette notification.');
        }

        $notification->setIsRead(true);
        $entityManager->flush();

        if ($notification->getLink()) {
            return $this->redirect($notification->getLink());
        }

        return $this->redirectToRoute('app_notifications');
    }
} 