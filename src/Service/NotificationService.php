<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function notifyAllUsers(string $message, string $type, ?string $link = null): void
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                $this->createNotification($user, $message, $type, $link);
            }
        }

        $this->entityManager->flush();
    }

    private function createNotification(User $user, string $message, string $type, ?string $link = null): void
    {
        $notification = new Notification();
        $notification->setUser($user)
            ->setMessage($message)
            ->setType($type)
            ->setLink($link);

        $this->entityManager->persist($notification);
    }
} 