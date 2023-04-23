<?php

namespace App\Controller;

use App\Entity\DTO\LectureDTO;
use App\Entity\Event;
use App\Entity\Lecture;
use App\Form\LectureType;
use App\Repository\LectureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Doctrine\Persistence\ManagerRegistry;


/**
 * Lectures
 * 
 * @OA\Tag (name="Lectures")
 */
#[Route('/api/lectures', name: 'api_lecture_')]
class LectureAPIController extends AbstractController
{
    /**
     * Get all lectures by Event
     * 
     * @OA\Tag (name="Lectures")
     * @OA\Response(
     *      response=200,
     *      description="Array of Lectures",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(type="integer", property="id"),
     *              @OA\Property(type="integer", property="eventId"),
     *              @OA\Property(type="string", property="lecturer"),
     *              @OA\Property(type="string", property="location"),
     *              @OA\Property(type="integer", property="attendeesQuantity"),
     *              @OA\Property(type="string", property="subtitle"),
     *              @OA\Property(type="string", property="description"),
     *              @OA\Property(type="datetime", property="startDate"),
     *              @OA\Property(type="datetime", property="endDate"),
     *              @OA\Property(type="string", property="lecturerImage"),
     *              @OA\Property(type="datetime", property="createdAt"),
     *              @OA\Property(type="datetime", property="updatedAt"),
     *          )
     *      )
     * )
     */
    #[Route('/by-event/{id}', name: 'api_lecture_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function index(Request $request, LectureRepository $lectureRepository): Response
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $lectures = [];
        foreach( $lectureRepository->findBy(['event_id' => $request->get('id')]) as $lecture )
        {
            $dto = new LectureDTO();
            $dto->setId( $lecture->getId() );
            $dto->setEventId( $lecture->getEvent()->getId() );
            $dto->setLecturer( $lecture->getLecturer() );
            $dto->setLocation( $lecture->getLocation() );
            $dto->setAttendeesQuantity( $lecture->getAttendeesQuantity() );
            $dto->setSubtitle( $lecture->getSubtitle() );
            $dto->setDescription( $lecture->getDescription() );
            $dto->setStartDate( $lecture->getStartDate() );
            $dto->setEndDate( $lecture->getEndDate() );
            $dto->setLecturerImage( $base_url . $lecture->getLecturerImage() );
            $dto->setCreatedAt( $lecture->getCreatedAt() );
            $dto->setUpdatedAt( $lecture->getUpdatedAt() );

            $lectures[] = $dto;
        }

        return $this->json($lectures);
    }

    /**
     * Creates a new Lecture
     * 
     * @OA\Tag (name="Lectures")
     * @OA\RequestBody(
     *      description="Lecture to add.<br><br>Comments:<br><ul><li><strong>lecturerImage:</strong> send a string in base64 format</li></ul>",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="eventId"),
     *          @OA\Property(type="string", property="lecturer"),
     *          @OA\Property(type="string", property="location"),
     *          @OA\Property(type="integer", property="attendeesQuantity"),
     *          @OA\Property(type="string", property="subtitle"),
     *          @OA\Property(type="string", property="description"),
     *          @OA\Property(type="datetime", property="startDate"),
     *          @OA\Property(type="datetime", property="endDate"),
     *          @OA\Property(type="string", format="binary", property="lecturerImage"),
     *      )
     * )
     * 
     * @TODO: validar estado do objeto antes de salvar
     */
    #[Route('', name: 'api_lecture_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function new(Request $request, LectureRepository $lectureRepository, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $date_insert = new \DateTimeImmutable('now');
        $event = $doctrine->getRepository(Event::class)->find($data['eventId']);
        $file_name = $this->uploadImage( $data['lecturerImage'] );

        $lecture = new Lecture();
        $lecture->setEvent($event);
        $lecture->setLecturer( $data['lecturer'] );
        $lecture->setLocation( $data['location'] );
        $lecture->setAttendeesQuantity( $data['attendeesQuantity'] );
        $lecture->setSubtitle( $data['subtitle'] );
        $lecture->setDescription( $data['description'] );
        $lecture->setStartDate( new \DateTime( $data['startDate'] ) );
        $lecture->setEndDate( new \DateTime( $data['endDate'] ) );
        $lecture->setLecturerImage( $file_name );
        $lecture->setCreatedAt( $date_insert );
        $lecture->setUpdatedAt( $date_insert );

        if ( $validator->validate($lecture) ) {
            $lectureRepository->save($lecture, true);

            return $this->json('Created new lecture successfully with id ' . $lecture->getId(), 201);
        }

        return $this->json('An error occurred while trying to save the lecture', 500);
    }

    /**
     * Get a lecture data
     * 
     * @OA\Tag (name="Lectures")
     * @OA\Response(
     *      response=200,
     *      description="Data of a lecture",
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="id"),
     *          @OA\Property(type="integer", property="eventId"),
     *          @OA\Property(type="string", property="lecturer"),
     *          @OA\Property(type="string", property="location"),
     *          @OA\Property(type="integer", property="attendeesQuantity"),
     *          @OA\Property(type="string", property="subtitle"),
     *          @OA\Property(type="string", property="description"),
     *          @OA\Property(type="datetime", property="startDate"),
     *          @OA\Property(type="datetime", property="endDate"),
     *          @OA\Property(type="string", property="lecturerImage"),
     *          @OA\Property(type="datetime", property="createdAt"),
     *          @OA\Property(type="datetime", property="updatedAt"),
     *      )
     * )
     */
    #[Route('/{id}', name: 'api_lecture_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function show(Lecture $lecture): Response
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $dto = new LectureDTO();
        $dto->setId( $lecture->getId() );
        $dto->setEventId( $lecture->getEvent()->getId() );
        $dto->setLecturer( $lecture->getLecturer() );
        $dto->setLocation( $lecture->getLocation() );
        $dto->setAttendeesQuantity( $lecture->getAttendeesQuantity() );
        $dto->setSubtitle( $lecture->getSubtitle() );
        $dto->setDescription( $lecture->getDescription() );
        $dto->setStartDate( $lecture->getStartDate() );
        $dto->setEndDate( $lecture->getEndDate() );
        $dto->setLecturerImage( $base_url . $lecture->getLecturerImage() );
        $dto->setCreatedAt( $lecture->getCreatedAt() );
        $dto->setUpdatedAt( $lecture->getUpdatedAt() );

        return $this->json($dto);
    }

    /**
     * Edit a lecture data
     * 
     * @OA\Tag (name="Lectures")
     * @OA\RequestBody(
     *      description="Lecture to add.<br><br>Comments:<br><ul><li><strong>lecturerImage:</strong> send a string in base64 format</li></ul>",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="lecturer"),
     *          @OA\Property(type="string", property="location"),
     *          @OA\Property(type="integer", property="attendeesQuantity"),
     *          @OA\Property(type="string", property="subtitle"),
     *          @OA\Property(type="string", property="description"),
     *          @OA\Property(type="datetime", property="startDate"),
     *          @OA\Property(type="datetime", property="endDate"),
     *          @OA\Property(type="string", format="binary", property="lecturerImage"),
     *      )
     * )
     * 
     * @TODO: validar estado do objeto antes de salvar
     */
    #[Route('/{id}', name: 'api_lecture_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function edit(Request $request, Lecture $lecture, LectureRepository $lectureRepository, ValidatorInterface $validator): Response
    {
        if (!$lecture) {
            return $this->json('No lecture found for id' . $request->get('id'), 404);
        }

        $data = json_decode($request->getContent(), true);

        $date_insert = new \DateTimeImmutable('now');

        $lecture = new Lecture();
        $lecture->setLecturer( $data['lecturer'] );
        $lecture->setLocation( $data['location'] );
        $lecture->setAttendeesQuantity( $data['attendeesQuantity'] );
        $lecture->setSubtitle( $data['subtitle'] );
        $lecture->setDescription( $data['description'] );
        $lecture->setStartDate( new \DateTime( $data['startDate'] ) );
        $lecture->setEndDate( new \DateTime( $data['endDate'] ) );
        $lecture->setUpdatedAt( $date_insert );

        if( $data['lecturerImage'] && base64_encode(base64_decode($data['lecturerImage'], true)) === $data['lecturerImage'] )
        {
            $file_name = $this->uploadImage( $data['lecturerImage'] );
            $lecture->setLecturerImage( $file_name );
        }

        if ( $validator->validate($lecture) ) {
            $lectureRepository->save($lecture, true);

            $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/';

            $dto = new LectureDTO();
            $dto->setId( $lecture->getId() );
            $dto->setEventId( $lecture->getEvent()->getId() );
            $dto->setLecturer( $lecture->getLecturer() );
            $dto->setLocation( $lecture->getLocation() );
            $dto->setAttendeesQuantity( $lecture->getAttendeesQuantity() );
            $dto->setSubtitle( $lecture->getSubtitle() );
            $dto->setDescription( $lecture->getDescription() );
            $dto->setStartDate( $lecture->getStartDate() );
            $dto->setEndDate( $lecture->getEndDate() );
            $dto->setLecturerImage( $base_url . $lecture->getLecturerImage() );
            $dto->setCreatedAt( $lecture->getCreatedAt() );
            $dto->setUpdatedAt( $lecture->getUpdatedAt() );

            return $this->json($dto);
        }

        return $this->json('An error occurred while trying to edit the lecture', 500);
    }

    /**
     * Deletes a Lecture
     * @OA\Tag (name="Lectures")
     */
    #[Route('/{id}', name: 'api_lecture_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function delete(Request $request, Lecture $lecture, LectureRepository $lectureRepository): Response
    {
        if (!$lecture) {
            return $this->json('No lecture found for id' . $request->get('id'), 404);
        }

        $lectureRepository->remove($lecture, true);
        return $this->json('Deleted a lecture successfully with id ' . $request->get('id') );
    }

    /**
     * Upload an image to the media dir
     */
    protected function uploadImage(string $image): string
    {
        $file_name = '';

        if( $image && base64_encode(base64_decode($image, true)) === $image )
        {
            /* Imagem */
            $data = base64_decode($image);
            $img = imagecreatefromstring($data);
            if( $img !== false )
            {
                $file_name = $this->getParameter('app.media_lecture_images') . uniqid() . '.jpg';
                imagejpeg($img, $file_name);
            }
        }

        return $file_name;
    }
}
