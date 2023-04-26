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
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;

#[Route('/code')]
class CodeController extends AbstractController
{
    #[Route('/{id_lecture}', name: 'app_code_index', methods: ['GET'])]
    public function index(Request $request, CodeRepository $codeRepository, ManagerRegistry $doctrine): Response
    {
        $codes = $codeRepository->findBy(['lecture' => $request->get('id_lecture')]);

        if( !$codes || count($codes) <= 0 )
        {
            $this->generateCodes( $request->get('id_lecture'), $codeRepository, $doctrine);
            $codes = $codeRepository->findBy(['lecture' => $request->get('id_lecture')]);
        }

        $qrCodes = [];
        foreach( $codes as $code ) {
            $qrCodes[$code->getId()] = $this->generateQRCode( $code );
        }

        return $this->render('@backend/code/list_qrcodes.html.twig', [
            'base_url' => (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'],
            'codes' => $codes,
            'qrCodes' => $qrCodes
        ]);
    }

    protected function generateCodes(int $lecture_id, CodeRepository $codeRepository, ManagerRegistry $doctrine)
    {
        $lecture = $doctrine->getRepository(Lecture::class)->find( $lecture_id );
        $date_insert = new \DateTimeImmutable('now');

        if( $lecture )
        {
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
            }
        }
    }

    protected function generateQRCode(Code $code)
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'];
        $output = new PngWriter();
        $qrCode = QrCode::create($base_url . $code->getUrl() )
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(512)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $label = Label::create('')->setFont(new NotoSans(44));
 
        return $output->write(
                                $qrCode,
                                null,
                                $label->setText(strtoupper( $code->getHash() ))
                            )->getDataUri();
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
