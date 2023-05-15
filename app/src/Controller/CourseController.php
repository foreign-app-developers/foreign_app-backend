<?php

namespace App\Controller;

use App\Entity\Course;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


/**
 * @Route("/api")
 */
class CourseController extends AbstractController
{


    public function getAllCourses(): JsonResponse
    {


        $courses = $this->getDoctrine()->getRepository(Course::class)->findAll();

        return $this->json($courses);
    }
    /**
     * @Route("/course/add", name="add_course", methods={"POST"})
     */
    public function addCourse(Request $request, CourseRepository $repo):JsonResponse
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw new AccessDeniedException('Доступ запрещен, требуется ROLE_TEACHER');
        }
        $data = json_decode($request->getContent(), true);
        // Создание экземпляра сущности Course
        $course = new Course();
        $course->setName($data['name']);
        $course->setDescription($data['description']);
        $course->setCreatedBy($data['createdBy']);
        $course->setStatus("on_consider");

        $repo->save($course, True);

        //возвращаем ответ в формате json
        return $this->json([
            'data' => $course,
            'message' => 'Курс успешно создан!',
        ]);

    }

    /**
     * @Route("/course", name="edit_course", methods={"PUT"})
     */
    public function editCourse(CourseRepository $repo, Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw new AccessDeniedException('Доступ запрещен, требуется ROLE_TEACHER');
        }
        $data = json_decode($request->getContent(), true);

        if (!array_key_exists('id', $data)) return $this->json([
            'message' => 'Вы не передали id',
        ], 400);

        $course = $repo->find($data['id']);
        if (!$course instanceof Course) return $this->json([
            'message' => 'Курс не найдена',
        ], 404);


        if (array_key_exists('description', $data))
            $course->setDescription($data['description']);
        if (array_key_exists('name', $data))
            $course->setName($data['name']);

        $repo->save($course, true);

        return $this->json([
            'data' => $course,
            'message' => 'Курс успешно обновлён!',
        ]);
    }
    /**
     * @Route("/course/{id}", name="get_course", methods={"GET"})
     */
    public function getCourse(int $id, CourseRepository $repo): JsonResponse
    {
        $course = $repo->find($id);

        if (!$course) {
            return $this->json([
                'message' => 'Курс не найден',
            ], 404);
        }

        return $this->json($course);
    }
    /**
     * @Route("/course/{id}", name="delete_course", methods={"DELETE"})
     */
    public function deleteCourse(int $id,CourseRepository $repo, #[CurrentUser] User $user): JsonResponse
    {
        if (!($user->isDirector())) {
            throw new AccessDeniedException('Доступ запрещен, требуется ROLE_DIRECTOR');
        }
        //ищем курс в репозитории по id
        $course = $repo->find($id);
        if (!$course instanceof Course) {
            return $this->json([
                'message' => 'Курс не найден!',
            ], 404);
        }
        // Удаляем курс из базы данных
        $repo->remove($course, true);
        return $this->json([
            'message' => 'Курс успешно удален!',
        ]);
    }

}
