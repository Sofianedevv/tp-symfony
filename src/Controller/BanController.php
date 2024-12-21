<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class BanController extends AbstractController
{
    #[Route('/banned', name: 'app_banned')]
    public function banned(Security $security): Response
    {
        // Déconnecter l'utilisateur
        if ($this->getUser()) {
            $security->logout(false);
        }

        return $this->render('ban/banned.html.twig');
    }
} 