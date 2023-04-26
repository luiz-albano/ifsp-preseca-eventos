<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\Lecture;
use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/attendee')]
class AttendeeController extends AbstractController
{
    #[Route('/validate/{hash}', name: 'app_attendee_validate', methods: ['GET'])]
    public function index(Request $request, CodeRepository $codeRepository, ManagerRegistry $doctrine): Response
    {
        $code = $codeRepository->findOneBy(['hash' => $request->get('hash')]);
        return $this->render('attendee/index.html.twig', [
            'code' => $code,
        ]);
    }
}
