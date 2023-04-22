<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\DTO\EventDTO;
use App\Form\EventType;
use App\Repository\EventRepository;
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



/**
 * Events
 * 
 * @OA\Tag (name="Events")
 */
#[Route('/api/events', name: 'api_event_')]
class EventAPIController extends AbstractController
{
    /**
     * Get all events
     * 
     * @OA\Tag (name="Events")
     * @OA\Response(
     *      response=200,
     *      description="Array of Events",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(type="integer", property="id"),
     *              @OA\Property(type="string", property="name"),
     *              @OA\Property(type="string", property="description"),
     *              @OA\Property(type="datetime", property="start_date"),
     *              @OA\Property(type="datetime", property="end_date"),
     *              @OA\Property(type="string", property="banner_url"),
     *              @OA\Property(type="datetime", property="createdAt"),
     *              @OA\Property(type="datetime", property="updatedAt"),
     *          )
     *      )
     * )
     */
    #[Route('', name: 'api_event_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function index(EventRepository $eventRepository): Response
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $events = [];
        foreach( $eventRepository->findAll() as $event )
        {
            $dto = new EventDTO();
            $dto->setId( $event->getId() );
            $dto->setName( $event->getName() );
            $dto->setDescription( $event->getDescription() );
            $dto->setStartDate( $event->getStartDate() );
            $dto->setEndDate( $event->getEndDate() );
            $dto->setBannerUrl( $base_url . $event->getBannerUrl() );
            $dto->setCreatedAt( $event->getCreatedAt() );
            $dto->setUpdatedAt( $event->getUpdatedAt() );

            $events[] = $dto;
        }

        return $this->json($events);
    }

    /**
     * Creates a new Event
     * 
     * @OA\Tag (name="Events")
     * @OA\RequestBody(
     *      description="Event to add.<br><br>Comments:<br><ul><li><strong>banner:</strong> send a string in base64 format</li></ul>",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="name"),
     *          @OA\Property(type="string", property="description"),
     *          @OA\Property(type="datetime", property="start_date"),
     *          @OA\Property(type="datetime", property="end_date"),
     *          @OA\Property(type="string", format="binary", property="banner"),
     *      )
     * )
     * 
     * @TODO: validar estado do objeto antes de salvar
     */
    #[Route('', name: 'api_event_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function new(Request $request, EventRepository $eventRepository, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $event = new Event();
        $date_insert = new \DateTimeImmutable('now');
        $event->setName( $data['name'] );
        $event->setDescription( $data['description'] );
        $event->setStartDate( new \DateTime( $data['start_date'] ) );
        $event->setEndDate( new \DateTime( $data['end_date'] ) );
        $event->setCreatedAt( $date_insert );
        $event->setUpdatedAt( $date_insert );

        /* Imagem */
        if( $data['banner'] && base64_encode(base64_decode($data['banner'], true)) === $data['banner'] )
        {
            $data = base64_decode($data['banner']);
            $img = imagecreatefromstring($data);
            if( $img !== false )
            {
                $file_name = $this->getParameter('app.media_event_images') . uniqid() . '.jpg';
                imagejpeg($img, $file_name);
                $event->setBannerUrl( $file_name );
            }
        }
        
        if ( $validator->validate($event) ) {
            $eventRepository->save($event, true);

            return $this->json('Created new event successfully with id ' . $event->getId(), 201);
        }

        return $this->json('An error occurred while trying to save the event', 500);
    }

    /**
     * Get a event data
     * 
     * @OA\Tag (name="Events")
     * @OA\Response(
     *      response=200,
     *      description="Data of a event",
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="id"),
     *          @OA\Property(type="string", property="name"),
     *          @OA\Property(type="string", property="description"),
     *          @OA\Property(type="datetime", property="start_date"),
     *          @OA\Property(type="datetime", property="end_date"),
     *          @OA\Property(type="string", property="banner_url"),
     *          @OA\Property(type="datetime", property="createdAt"),
     *          @OA\Property(type="datetime", property="updatedAt"),
     *      )
     * )
     */
    #[Route('/{id}', name: 'api_event_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function show(Event $event): Response
    {
        $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/';

        $dto = new EventDTO();
        $dto->setId( $event->getId() );
        $dto->setName( $event->getName() );
        $dto->setDescription( $event->getDescription() );
        $dto->setStartDate( $event->getStartDate() );
        $dto->setEndDate( $event->getEndDate() );
        $dto->setBannerUrl( $base_url . $event->getBannerUrl() );
        $dto->setCreatedAt( $event->getCreatedAt() );
        $dto->setUpdatedAt( $event->getUpdatedAt() );

        return $this->json($dto);
    }

    
    /**
     * Edit a event data
     * 
     * @OA\Tag (name="Events")
     * @OA\RequestBody(
     *      description="Event to edit.<br><br>Comments:<br><ul><li><strong>banner:</strong> send a string in base64 format</li></ul>",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="name"),
     *          @OA\Property(type="string", property="description"),
     *          @OA\Property(type="datetime", property="start_date"),
     *          @OA\Property(type="datetime", property="end_date"),
     *          @OA\Property(type="string", format="binary", property="banner"),
     *      )
     * )
     * 
     * @TODO: validar estado do objeto antes de salvar
     */
    #[Route('/{id}', name: 'api_event_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function edit(Request $request, Event $event, EventRepository $eventRepository, ValidatorInterface $validator): Response
    {
        if (!$event) {
            return $this->json('No event found for id' . $request->get('id'), 404);
        }

        $data = json_decode($request->getContent(), true);

        $event = new Event();
        $date_insert = new \DateTimeImmutable('now');
        $event->setName( $data['name'] );
        $event->setDescription( $data['description'] );
        $event->setStartDate( $data['start_date'] );
        $event->setEndDate( $data['end_date'] );
        $event->setUpdatedAt( $date_insert );

        if( $data['banner'] && base64_encode(base64_decode($data['banner'], true)) === $data['banner'] )
        {
            /* Imagem */
            $data = base64_decode($data['banner']);
            $img = imagecreatefromstring($data);
            if( $img !== false )
            {
                $file_name = $this->getParameter('app.media_event_images') . uniqid() . '.jpg';
                imagejpeg($img, $file_name);
                $event->setBannerUrl( $file_name );
            }
        }

        if ( $validator->validate($event) ) {
            $eventRepository->save($event, true);

            $base_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/';

            $dto = new EventDTO();
            $dto->setId( $event->getId() );
            $dto->setName( $event->getName() );
            $dto->setDescription( $event->getDescription() );
            $dto->setStartDate( $event->getStartDate() );
            $dto->setEndDate( $event->getEndDate() );
            $dto->setBannerUrl( $base_url . $event->getBannerUrl() );
            $dto->setCreatedAt( $event->getCreatedAt() );
            $dto->setUpdatedAt( $event->getUpdatedAt() );

            return $this->json($dto);
        }

        return $this->json('An error occurred while trying to edit the course', 500);
    }

    /**
     * Deletes a Event
     */
    #[Route('/{id}', name: 'api_event_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function delete(Request $request, Event $event, EventRepository $eventRepository): Response
    {
        if (!$event) {
            return $this->json('No event found for id' . $request->get('id'), 404);
        }

        $eventRepository->remove($event, true);

        return $this->json('Deleted a event successfully with id ' . $request->get('id') );
    }
}
