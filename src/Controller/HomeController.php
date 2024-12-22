<?php

namespace App\Controller;

use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, SubjectRepository $subjectRepository, PaginatorInterface $paginator): Response
    {
        $query = $request->query->get('q');
        
        if ($query) {
            $queryBuilder = $subjectRepository->createQueryBuilderBySearch($query);
        } else {
            $queryBuilder = $subjectRepository->createQueryBuilder('s')
                ->orderBy('s.name', 'ASC');
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('home/index.html.twig', [
            'subjects' => $pagination,
            'query' => $query,
        ]);
    }
}
