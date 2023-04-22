<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/api', name: 'api_')]
class TestApiController extends AbstractController
{
    /**
     * Descrição do endpoint - teste
     */
    #[Route('/test', name: 'app_test_api', methods:["POST"] )]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'Access denied')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DashboardController.php',
        ]);
    }
}
