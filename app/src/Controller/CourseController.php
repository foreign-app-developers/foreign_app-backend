<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseForUserRepository;
use App\Entity\CourseForUser;
use Symfony\Component\HttpClient\CurlHttpClient;
use App\Entity\Task;
use App\Repository\CourseRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @Route("/api")
 */
class CourseController extends AbstractController
{

    /**
     * @Route("/courses", name="get_courses", methods={"GET"})
     */
    public function getAllCourses(CourseRepository $repo): JsonResponse
    {

        $courses = $repo->findAll();
        return $this->json($courses);

    }
    /**
     * @Route("/course/add", name="add_course", methods={"POST"})
     */
    public function addCourse(Request $request, CourseRepository $repo):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            throw new AccessDeniedHttpException('Заголовок Authorization не предоставлен');
        }
        $client = HttpClient::create();
        $headers = [
            'YT-AUTH-TOKEN' => "YourTar " . $authorizationHeader
        ];
        $response = $client->request('GET', 'https://back.yourtar.ru/api/user/?with_project=1', [
            'headers' => $headers,
        ]);
        $usrData = json_decode($response->getContent(), true);
        if (!((in_array('ROLE_TEACHER' , $usrData['data']['roles'])) or (in_array('ROLE_DIRECTOR' , $usrData['data']['roles']))))  {
            throw new AccessDeniedException('Доступ запрещен, требуется роль учителя или директора');
        }
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
        $data = json_decode($request->getContent(), true);
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            throw new AccessDeniedHttpException('Заголовок Authorization не предоставлен');
        }
        $client = HttpClient::create();
        $headers = [
            'YT-AUTH-TOKEN' => "YourTar " . $authorizationHeader
        ];
        $response = $client->request('GET', 'https://back.yourtar.ru/api/user/?with_project=1', [
            'headers' => $headers,
        ]);
        $usrData = json_decode($response->getContent(), true);
        if (!((in_array('ROLE_TEACHER' , $usrData['data']['roles'])) or (in_array('ROLE_DIRECTOR' , $usrData['data']['roles']))))  {
            throw new AccessDeniedException('Доступ запрещен, требуется роль учителя или директора');
        }

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
     * @Route("/course_by/{id}", name="get_courses_for_student", methods={"GET"})
     */
    public function getCoursesForStudent(int $id, CourseRepository $repo): JsonResponse
    {
        $courses = $repo->findBy(['created_by' => $id]);

        $courseData = [];
        foreach ($courses as $course) {
            $courseData[] = [
                'id' => $course->getId(),
                'name' => $course->getName(),
                'status' => $course->getStatus(),
                'description' => $course ->getDescription()
            ];
        }

        return $this->json($courseData);
    }

    /**
     * @Route("/student-courses", name="student_courses", methods={"GET"})
     */
    public function getStudentCourses( CourseForUserRepository $courseForUserRepository, Request $request, CourseRepository $repo)
    {

        $authorizationHeader = $request-> headers->get('Authorization');

        if (!$authorizationHeader) {
            throw new AccessDeniedException('Заголовок Authorization не предоставлен');
        }
        $client = HttpClient::create();
        $headers = [
            'YT-AUTH-TOKEN' => "YourTar " . $authorizationHeader
        ];
        $response = $client->request('GET', 'https://back.yourtar.ru/api/user/?with_project=1', [
            'headers' => $headers,
        ]);

        $usrData = json_decode($response->getContent(), true);

        $id = $usrData['data']['id'];
        $studentCourses = $courseForUserRepository->findCoursesForStudent($id);
        $coursesData = [];

        if ($studentCourses !== null) {
            foreach ($studentCourses as $courseForUser) {
                $course = $repo ->find($courseForUser->getCourseId());
                $coursesData[] = [
                    'id' => $course->getId(),
                    'created_by' => $course->getCreatedBy(),
                    'name' => $course->getName(),
                    'description' => $course->getDescription(),
                    'status' => $course->getStatus(),
                ];
            }
        }

        return $this->json(['data' => $coursesData]);

    }

    /**
     * @Route("/assign-course/{courseId}/{userId}", name="assign_course_to_student", methods={"POST"})
     */
    public function assignCourseToStudent(int $courseId, int $userId, CourseRepository $courseRepository, UserRepository $userRepository, CourseForUserRepository $courseForUserRepository,Request $request): JsonResponse
    {
        //Добавить получение id пользователя от фронта
        $course = $courseRepository->find($courseId);
        $student = $userRepository->find($userId);

        if (!$course) {
            return new JsonResponse(['message' => 'Курс не найден'], 404);
        }

        if (!$student) {
            return new JsonResponse(['message' => 'Студент не найден'], 404);
        }

        $courseForUser = new CourseForUser();
        $courseForUser->setCourseId($courseId);
        $courseForUser->setUserId($userId);

        $courseForUserRepository->save($courseForUser, true);

        //возвращаем ответ в формате json
        return $this->json([
            'data' => $courseForUser,
            'message' => 'Курс успешно назначен!',
        ]);
    }

    /**
     * @Route("/course/{id}", name="delete_course", methods={"DELETE"})
     */
    public function deleteCourse(int $id,CourseRepository $repo, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            throw new AccessDeniedHttpException('Заголовок Authorization не предоставлен');
        }
        $client = HttpClient::create();
        $headers = [
            'YT-AUTH-TOKEN' => "YourTar " . $authorizationHeader
        ];
        $response = $client->request('GET', 'https://back.yourtar.ru/api/user/?with_project=1', [
            'headers' => $headers,
        ]);
        $usrData = json_decode($response->getContent(), true);
        if (!((in_array('ROLE_TEACHER' , $usrData['data']['roles'])) or (in_array('ROLE_DIRECTOR' , $usrData['data']['roles']))))  {
            throw new AccessDeniedException('Доступ запрещен, требуется роль учителя или директора');
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
