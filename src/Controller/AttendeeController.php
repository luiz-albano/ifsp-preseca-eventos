<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AttendeeController extends AbstractController
{
    #[Route('/attendee', name: 'app_attendee')]
    public function index(): Response
    {
        return $this->render('attendee/index.html.twig', [
            'controller_name' => 'AttendeeController',
        ]);
    }
}
