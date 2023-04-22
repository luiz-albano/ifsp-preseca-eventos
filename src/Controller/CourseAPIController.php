<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\DTO\CourseDTO;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Courses
 * 
 * @OA\Tag (name="Courses")
 */
#[Route('/api/courses', name: 'api_course_')]
class CourseAPIController extends AbstractController
{
    /**
     * Get all courses
     * 
     * @OA\Tag (name="Courses")
     * @OA\Response(
     *      response=200,
     *      description="Array of courses",
     *      @OA\JsonContent(
     *          type="array",
     *          @OA\Items(
     *              @OA\Property(type="integer", property="id"),
     *              @OA\Property(type="string", property="class"),
     *              @OA\Property(type="string", property="period"),
     *              @OA\Property(type="datetime", property="createdAt"),
     *              @OA\Property(type="datetime", property="updatedAt"),
     *          )
     *      )
     * )
     */
    #[Route('', name: 'api_course_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = [];
        foreach( $courseRepository->findAll() as $course )
        {
            $dto = new CourseDTO();
            $dto->setId( $course->getId() );
            $dto->setClass( $course->getClass() );
            $dto->setPeriod( $course->getPeriod() );
            $dto->setCreatedAt( $course->getCreatedAt() );
            $dto->setUpdatedAt( $course->getUpdatedAt() );

            $courses[] = $dto;

        }

        return $this->json($courses);
    }


    /**
     * Creates a new course
     * 
     * @OA\Tag (name="Courses")
     * @OA\RequestBody(
     *      description="Course to add",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="class"),
     *          @OA\Property(type="string", property="period")
     *      )
     * )
     * 
     * @TODO: validar estado do objeto antes de salvar
     */
    #[Route('', name: 'api_course_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function new(Request $request, CourseRepository $courseRepository, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $course = new Course();
        $date_insert = new \DateTimeImmutable('now');
        $course->setClass( $data['class'] );
        $course->setPeriod( $data['period'] );
        $course->setCreatedAt( $date_insert );
        $course->setUpdatedAt( $date_insert );

        if ( $validator->validate($course) ) {
            $courseRepository->save($course, true);

            return $this->json('Created new course successfully with id ' . $course->getId(), 201);
        }

        return $this->json('An error occurred while trying to save the course', 500);
    }


    /**
     * Get a course data
     * 
     * @OA\Tag (name="Courses")
     * @OA\Response(
     *      response=200,
     *      description="Data of a course",
     *      @OA\JsonContent(
     *          @OA\Property(type="integer", property="id"),
     *          @OA\Property(type="string", property="class"),
     *          @OA\Property(type="string", property="period"),
     *          @OA\Property(type="datetime", property="createdAt"),
     *          @OA\Property(type="datetime", property="updatedAt"),
     *      )
     * )
     */
    #[Route('/{id}', name: 'api_course_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function show(Course $course): Response
    {
        $dto = new CourseDTO();
        $dto->setId( $course->getId() );
        $dto->setClass( $course->getClass() );
        $dto->setPeriod( $course->getPeriod() );
        $dto->setCreatedAt( $course->getCreatedAt() );
        $dto->setUpdatedAt( $course->getUpdatedAt() );

        return $this->json($dto);
    }


    /**
     * Edit a course data
     * 
     * @OA\Tag (name="Courses")
     * @OA\RequestBody(
     *      description="Course to add",
     *      required=true,
     *      @OA\JsonContent(
     *          @OA\Property(type="string", property="class"),
     *          @OA\Property(type="string", property="period")
     *      )
     * )
     * 
     * @TODO: validar estado do objeto antes de salvar
     */
    #[Route('/{id}', name: 'api_course_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function edit(Request $request, Course $course, CourseRepository $courseRepository, ValidatorInterface $validator): Response
    {       
        if (!$course) {
            return $this->json('No course found for id' . $request->get('id'), 404);
        }

        $data = json_decode($request->getContent(), true);

        $date_insert = new \DateTimeImmutable('now');
        $course->setClass( $data['class'] );
        $course->setPeriod( $data['period'] );
        $course->setUpdatedAt( $date_insert );

        if ( $validator->validate($course) ) {
            $courseRepository->save($course, true);

            $dto = new CourseDTO();
            $dto->setId( $course->getId() );
            $dto->setClass( $course->getClass() );
            $dto->setPeriod( $course->getPeriod() );
            $dto->setCreatedAt( $course->getCreatedAt() );
            $dto->setUpdatedAt( $course->getUpdatedAt() );

            return $this->json($dto);
        }

        return $this->json('An error occurred while trying to edit the course', 500);
    }


    /**
     * Deletes a course
     */
    #[Route('/{id}', name: 'api_course_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Access denied')]
    public function delete(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        if (!$course) {
            return $this->json('No course found for id' . $request->get('id'), 404);
        }

        $courseRepository->remove($course, true);

        return $this->json('Deleted a course successfully with id ' . $request->get('id') );
    }
}
