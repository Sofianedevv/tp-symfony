<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, SubjectRepository $subjectRepository): Response
    {
        $query = $request->query->get('q');
        
        if ($query) {
            $subjects = $subjectRepository->searchByName($query);
        } else {
            $subjects = $subjectRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'subjects' => $subjects,
            'query' => $query,
        ]);
    }
}
