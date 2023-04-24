<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\DTO\CodeDTO;
use App\Entity\Lecture;
use App\Form\CodeType;
use App\Repository\CodeRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\Persistence\ManagerRegistry;


/**
 * Codes
 * 
 * @OA\Tag (name="Codes")
 */
#[Route('/api/codes', name: 'api_code_')]
class CodeAPIController extends AbstractController
{

    /**
     * Get all codes by Lecture
     * 
     * @OA\Tag (name="Codes")
     * @OA\Response(
     *      response=200,
     *      description="Array of Codes from Lecture",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(type="integer", property="id"),
     *              @OA\Property(type="string", property="hash"),
     *              @OA\Property(type="string", property="url"),
     *              @OA\Property(type="object", property="used_by", @OA\Schema(ref=@Model(type=ParticipantDTO::class))),
     *              @OA\Property(type="integer", property="lectureId"),
     *              @OA\Property(type="datetime", property="createdAt"),
     *              @OA\Property(type="datetime", property="updatedAt")
     *          )
     *      )
     * )
     */
    #[Route('/{id_lecture}', name: 'api_code_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function index(Request $request, CodeRepository $codeRepository): Response
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'];

        $codes = [];
        foreach( $codeRepository->findBy(['lecture' => $request->get('id_lecture')]) as $code )
        {
            $codes[] = new CodeDTO( $code );
        }

        return $this->json($codes);
    }


    /**
     * Get a code data
     * 
     * @OA\Tag (name="Codes")
     * @OA\Response(
     *      response=200,
     *      description="Data of a code",
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="id"),
     *          @OA\Property(type="string", property="hash"),
     *          @OA\Property(type="string", property="url"),
     *          @OA\Property(type="object", property="used_by", @OA\Schema(ref=@Model(type=ParticipantDTO::class))),
     *          @OA\Property(type="integer", property="lectureId"),
     *          @OA\Property(type="datetime", property="createdAt"),
     *          @OA\Property(type="datetime", property="updatedAt")
     *      )
     * )
     */
    #[Route('/{hash}', name: 'api_code_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function show(Request $request, CodeRepository $codeRepository): Response
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'];

        $code = $codeRepository->findOneBy(['hash' => $request->get('hash')]);

        if( !$code ) {
            return $this->json('No code found for hash' . $request->get('hash'), 404);
        }

        $dto = new CodeDTO();
        $dto->setId( $code->getId() );
        $dto->setHash( $code->getHash() );
        $dto->setUrl( $base_url . $code->getUrl() );
        $dto->setUsedBy( $code->getUsedBy() );
        $dto->setLecture($code->getLecture()->getId());
        $dto->setCreatedAt( $code->getCreatedAt() );
        $dto->setUpdatedAt( $code->getUpdatedAt() );

        return $this->json($dto);
    }


    /**
     * Generate codes for a Lecture
     * 
     * @OA\Tag (name="Codes")
     * @OA\RequestBody(
     *      description="Generate codes for a Lecture.",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="lectureId"),
     *          @OA\Property(type="integer", property="attendeesQuantity")
     *      )
     * )
     * @OA\Response(
     *      response=200,
     *      description="Array of Codes",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(type="integer", property="id"),
     *              @OA\Property(type="string", property="hash"),
     *              @OA\Property(type="string", property="url"),
     *              @OA\Property(type="integer", property="used_by"),
     *              @OA\Property(type="integer", property="lectureId"),
     *              @OA\Property(type="datetime", property="createdAt"),
     *              @OA\Property(type="datetime", property="updatedAt")
     *          )
     *      )
     * )
     */
    #[Route('/generate-codes', name: 'api_code_generate', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function generateCodes(Request $request, CodeRepository $codeRepository, ManagerRegistry $doctrine): Response
    {
        $data = json_decode($request->getContent(), true);

        //Verify if data was sended
        if( isset($data['lectureId']) && intval($data['lectureId']) <= 0 && isset($data['attendeesQuantity']) && intval($data['attendeesQuantity']) <= 0)
        {
            return $this->json('The fields "lectureId" and "attendeesQuantity" are required.', 500);
        }

        $date_insert = new \DateTimeImmutable('now');
        $lecture = $doctrine->getRepository(Lecture::class)->find($data['lectureId']);

        if (!$lecture) {
            return $this->json('No lecture found for id' . $data['lectureId'], 404);
        }

        $codes = [];
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'];

        for($i=0 ; $i < intval($data['attendeesQuantity']) ; $i++)
        {
            $hash = uniqid();

            $code = new Code();
            $code->setHash( $hash );
            $code->setUrl('/attendee/validate/' . $hash );
            $code->setLecture($lecture);
            $code->setCreatedAt($date_insert);
            $code->setUpdatedAt($date_insert);

            $codeRepository->save($lecture, true);

            $dto = new CodeDTO();
            $dto->setId( $code->getId() );
            $dto->setHash( $hash );
            $dto->setUrl( $base_url . '/attendee/validate/' . $hash );
            $dto->setLecture($code->getLecture()->getId());
            $dto->setCreatedAt($date_insert);
            $dto->setUpdatedAt($date_insert);

            $codes[] = $dto;
        }

        return $this->json($codes);
    }
}
