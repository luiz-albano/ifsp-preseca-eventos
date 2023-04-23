<?php

namespace App\Controller;

use App\Entity\Code;
use App\Entity\Course;
use App\Entity\Participant;
use App\Entity\DTO\ParticipantDTO;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Attendees
 * 
 * @OA\Tag (name="Attendees")
 */
#[Route('/api/attendee', name: 'api_attendee_')]
class AttendeeAPIController extends AbstractController
{
    /**
     * Validates the confirmation of attendance of a participant
     * 
     * @OA\Tag (name="Codes")
     * @OA\RequestBody(
     *      description="Generate codes for a Lecture.",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="kind"),
     *          @OA\Property(type="string", property="ra"),
     *          @OA\Property(type="string", property="name"),
     *          @OA\Property(type="string", property="email"),
     *          @OA\Property(type="string", property="reason"),
     *          @OA\Property(type="string", property="accepted_terms"),
     *          @OA\Property(type="string", property="user_agent"),
     *          @OA\Property(type="string", property="ip"),
     *          @OA\Property(type="integer", property="course_id"),
     *      )
     * )
     * @OA\Response(
     *      response=200,
     *      description="Particpant data",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(type="integer", property="id"),
     *              @OA\Property(type="string", property="kind"),
     *              @OA\Property(type="string", property="ra"),
     *              @OA\Property(type="string", property="name"),
     *              @OA\Property(type="string", property="email"),
     *              @OA\Property(type="string", property="reason"),
     *              @OA\Property(type="string", property="accepted_terms"),
     *              @OA\Property(type="string", property="user_agent"),
     *              @OA\Property(type="string", property="ip"),
     *              @OA\Property(type="integer", property="course_id"),
     *          )
     *      )
     * )
     */
    #[Route('/validate/{hash}', name: 'api_attendee_validate', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function validate(Request $request, ParticipantRepository $participantRepository, ManagerRegistry $doctrine): Response
    {
        $data = json_decode($request->getContent(), true);

        //Get code data
        $code = $doctrine->getRepository(Code::class)->findOneBy(['hash' => $request->get('hash')]);

        if( !$code ) {
            return $this->json('No code found for hash' . $request->get('hash'), 404);
        }

        if( !$code->getUsedBy() ) {
            return $this->json('The code is already in use.');
        }

        $date_insert = new \DateTimeImmutable('now');

        //Register the participant
        $participant = new Participant();
        $participant->setKind( $data['kind'] );
        $participant->setRa( $data['ra'] );
        $participant->setName( $data['name'] );
        $participant->setEmail( $data['email'] );
        $participant->setReason( $data['reason'] );
        $participant->setAcceptedTerms( $data['accepted_terms'] );
        $participant->setUserAgent( $data['user_agent'] );
        $participant->setIp( $data['ip'] );
        $participant->addCode( $code );
        $participant->setCreatedAt( $date_insert );
        $participant->setUpdatedAt( $date_insert );

        //Get Course Data
        if( isset($data['course_id']) && intval( $data['course_id'] ) > 0 )
        {
            $course = $doctrine->getRepository(Course::class)->find(intval( $data['course_id']));
            if( !$course ) {
                return $this->json('No course found for id' . $data['course_id'], 404);
            }

            $participant->addCourse($course);
        }

        $participantRepository->save($participant, true);

        //Update the code
        $code->setUsedBy( $participant );
        $doctrine->getManager()->persist($code);

        $dto = new ParticipantDTO();
        $dto->setId( $participant->getId() );
        $dto->setKind( $participant->getKind() );
        $dto->setRa( $participant->getRa() );
        $dto->setName( $participant->getName() );
        $dto->setEmail( $participant->getEmail() );
        $dto->setReason( $participant->getReason() );
        $dto->setAcceptedTerms( $participant->getAcceptedTerms() );
        $dto->setUserAgent( $participant->getUserAgent() );
        $dto->setIp( $participant->getIp() );
        $dto->setCourseId( $data['course_id'] );
        $dto->setCreatedAt( $participant->getCreatedAt() );
        $dto->setUpdatedAt( $participant->getUpdatedAt() );

        return $this->json($dto);
    }

}
