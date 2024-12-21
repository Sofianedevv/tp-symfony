<?php

namespace App\Controller;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $subjects = $entityManager->getRepository(Subject::class)->findAll();

        return $this->render('home/index.html.twig', [
            'subjects' => $subjects,
        ]);
    }
}
