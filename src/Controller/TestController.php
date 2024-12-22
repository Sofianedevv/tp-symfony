<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-reset', name: 'test_reset')]
    public function testReset(): Response
    {
        return new Response('La route fonctionne !');
    }
} 