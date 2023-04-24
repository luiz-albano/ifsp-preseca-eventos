<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $events = $doctrine->getRepository(Event::class)->findAll();

        return $this->render('@backend/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'events' => $events,
            'events_count' => count($events)
        ]);
    }
}
