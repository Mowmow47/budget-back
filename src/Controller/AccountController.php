<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): JsonResponse
    {
        return $this->json([
            '0' => [
                'title' => 'Test 1',
                'content' => 'Je suis un premier contenu de test',
            ],
            '1' => [
                'title' => 'Test 2',
                'content' => 'Je suis un second contenu de test',
            ],
        ]);
    }
}
