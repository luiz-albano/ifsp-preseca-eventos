<?php

namespace App\Controller;

use App\Entity\Lecture;
use App\Entity\Event;
use App\Form\LectureType;
use App\Repository\LectureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/lecture')]
class LectureController extends AbstractController
{
    #[Route('/', name: 'app_lecture_index', methods: ['GET'])]
    public function index(LectureRepository $lectureRepository): Response
    {
        return $this->render('@backend/lecture/index.html.twig', [
            'lectures' => $lectureRepository->findAll(),
        ]);
    }

    #[Route('/new/{id_event}', name: 'app_lecture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LectureRepository $lectureRepository, ManagerRegistry $doctrine): Response
    {
        $lecture = new Lecture();
        $form = $this->createForm(LectureType::class, $lecture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $doctrine->getRepository(Event::class)->find($request->get('event_id'));
            $date_insert = new \DateTimeImmutable('now');
            
            $lecture->setEvent( $event );
            $lecture->setCreatedAt( $date_insert );
            $lecture->setUpdatedAt( $date_insert );

            $lectureRepository->save($lecture, true);

            return $this->redirectToRoute('app_event_show', ['id' => $request->get('id_event')], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('@backend/lecture/new.html.twig', [
            'lecture' => $lecture,
            'id_event' => $request->get('id_event'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/view/{id_event}', name: 'app_lecture_show', methods: ['GET'])]
    public function show(Request $request, Lecture $lecture): Response
    {
        return $this->render('@backend/lecture/show.html.twig', [
            'lecture' => $lecture,
            'id_event' => $request->get('id_event')
        ]);
    }

    #[Route('/{id}/edit/{id_event}', name: 'app_lecture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lecture $lecture, LectureRepository $lectureRepository): Response
    {
        $form = $this->createForm(LectureType::class, $lecture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date_insert = new \DateTimeImmutable('now');
            $lecture->setUpdatedAt( $date_insert );

            $lectureRepository->save($lecture, true);

            return $this->redirectToRoute('app_event_show', ['id' => $request->get('id_event')], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('@backend/lecture/edit.html.twig', [
            'lecture' => $lecture,
            'id_event' => $request->get('id_event'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lecture_delete', methods: ['POST'])]
    public function delete(Request $request, Lecture $lecture, LectureRepository $lectureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lecture->getId(), $request->request->get('_token'))) {
            $lectureRepository->remove($lecture, true);
        }

        return $this->redirectToRoute('app_lecture_index', [], Response::HTTP_SEE_OTHER);
    }
}
