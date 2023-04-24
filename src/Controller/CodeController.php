<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\Lecture;
use App\Form\CodeType;
use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/code')]
class CodeController extends AbstractController
{
    #[Route('/{id_lecture}', name: 'app_code_index', methods: ['GET'])]
    public function index(Request $request, CodeRepository $codeRepository, ManagerRegistry $doctrine): Response
    {
        $codes = $codeRepository->findBy(['lecture' => $request->get('id_lecture')]);

        if( !$codes || count($codes) <= 0 )
        {
            $codes = $this->generateCodes( $request->get('id_lecture'), $codeRepository, $doctrine);
        }

        return $this->render('@backend/code/index.html.twig', [
            'codes' => $codes,
        ]);
    }

    protected function generateCodes(int $lecture_id, CodeRepository $codeRepository, ManagerRegistry $doctrine)
    {
        $lecture = $doctrine->getRepository(Lecture::class)->find( $lecture_id );
        $date_insert = new \DateTimeImmutable('now');

        if( $lecture )
        {
            $codes = [];
            for( $i=0 ; $i < $lecture->getAttendeesQuantity() ; $i++)
            {
                $hash = uniqid();

                $code = new Code();
                $code->setHash( $hash );
                $code->setUrl('/attendee/validate/' . $hash );
                $code->setLecture($lecture);
                $code->setCreatedAt($date_insert);
                $code->setUpdatedAt($date_insert);

                $codeRepository->save($code, true);
                $codes = $code;
            }

            return $codes;
        }
    }

    #[Route('/new', name: 'app_code_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CodeRepository $codeRepository): Response
    {
        $code = new Code();
        $form = $this->createForm(CodeType::class, $code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $codeRepository->save($code, true);

            return $this->redirectToRoute('app_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('code/new.html.twig', [
            'code' => $code,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_code_show', methods: ['GET'])]
    public function show(Code $code): Response
    {
        return $this->render('code/show.html.twig', [
            'code' => $code,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_code_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Code $code, CodeRepository $codeRepository): Response
    {
        $form = $this->createForm(CodeType::class, $code);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $codeRepository->save($code, true);

            return $this->redirectToRoute('app_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('code/edit.html.twig', [
            'code' => $code,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_code_delete', methods: ['POST'])]
    public function delete(Request $request, Code $code, CodeRepository $codeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$code->getId(), $request->request->get('_token'))) {
            $codeRepository->remove($code, true);
        }

        return $this->redirectToRoute('app_code_index', [], Response::HTTP_SEE_OTHER);
    }
}
