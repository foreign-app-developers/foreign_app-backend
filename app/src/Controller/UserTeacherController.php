<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseForUserRepository;
use App\Repository\CourseRepository;
use App\Repository\UserTeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserTeacher;
use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class UserTeacherController extends AbstractController
{
    private $serializer;

    public function __construct( SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
    /**
     * @Route("/invite", name="send invite", methods={"POST"})
     */
    public function sendInvite(Request $request, UserTeacherRepository $repo):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            return $this->json([
                'message' => 'Заголовок Authorization не предоставлен',
            ], 401);
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
            return $this->json([
                'message' => 'Доступ запрещен, требуется роль учителя или директора',
            ], 403);
        }
        // Создание экземпляра сущности Course
        $invent = new UserTeacher();
        $invent->setTeacherId($usrData['data']['id']);
        $invent->setUserId($data['id']);
        $invent->setAccept(True);

        $existingInvitation = $repo->findOneBy(['teacher_id' => $usrData['data']['id'], 'user_id' => $data['id']]);

        if ($existingInvitation) {
            return $this->json([
                'message' => 'Приглашение уже отправлено для этого пользователя',
            ], 400);
        }
        $repo->save($invent, True);

        //возвращаем ответ в формате json
        return $this->json([
            'data' => $this->serializer->normalize($invent),
            'message' => 'Приглашение успешно создано!',
        ]);
    }
    /**
     * @Route("/getStudents", name="get_students_from_teacher", methods={"GET"})
     */
    public function getStudents(Request $request, UserTeacherRepository $repo, CourseForUserRepository $repository): JsonResponse
    {
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            return $this->json([
                'message' => 'Заголовок Authorization не предоставлен',
            ], 401);
        }
        $client = HttpClient::create();
        $headers = [
            'YT-AUTH-TOKEN' => "YourTar " . $authorizationHeader
        ];
        $response = $client->request('GET', 'https://back.yourtar.ru/api/user/?with_project=1', [
            'headers' => $headers,
        ]);
        $usrData = json_decode($response->getContent(), true);
        if (!((in_array('ROLE_TEACHER', $usrData['data']['roles'])) or (in_array('ROLE_DIRECTOR', $usrData['data']['roles'])))) {
            return $this->json([
                'message' => 'Доступ запрещен, требуется роль учителя или директора',
            ], 403);
        }
        $data = json_decode($request->getContent(), true);

        if ($data) {
            $course_id = $data['course_id'];
        }
        $students = $repo->findBy(['teacher_id' => $usrData['data']['id']]);
        $acceptedStudents = [];
        foreach ($students as $student) {
            if ($data) {
                $courseForUserEntry = $repository->findOneBy(['user_id' => $student->getUserId(), 'course_id' => $course_id]);

                if (!$courseForUserEntry) {
                    continue;
                }
            }
            if ($student->getAccept()) {

                $user = new User($student->getUserId(), 'student', 'Vovafelinger75@gmail.com');
                $acceptedStudents[] = $user;
            }
        }
        if ($data) {
            return $this->json([
                'data' => $this->serializer->normalize($acceptedStudents),
                'message' => 'Студенты получены!'
            ]);
        } else {
            return $this->json([
                'data' => $this->serializer->normalize($acceptedStudents),
                'message' => 'Все студенты получены!'
            ]);
        }
    }


    /**
     * @Route("/getTeachers", name="get_teachers_from_student", methods={"GET"})
     */
    public function getTeachers(Request $request, UserTeacherRepository $repo): JsonResponse
    {
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            return $this->json([
                'message' => 'Заголовок Authorization не предоставлен',
            ], 401);
        }
        $client = HttpClient::create();
        $headers = [
            'YT-AUTH-TOKEN' => "YourTar " . $authorizationHeader
        ];
        $response = $client->request('GET', 'https://back.yourtar.ru/api/user/?with_project=1', [
            'headers' => $headers,
        ]);
        $usrData = json_decode($response->getContent(), true);
        $teachers = $repo->findBy(['user_id' => $usrData['data']['id']]);
        $acceptedteachers = [];
        foreach ($teachers as $teacher) {
            if ($teacher->getAccept()) {
                $user = new User($teacher->getTeacherId(),'teacher', 'Vovafelinger75@gmail.com');
                $acceptedteachers[] = $user;
            }
        }
        return $this->json([
            'data'=> $this->serializer->normalize($acceptedteachers),
            'message'=> 'Преподаватели получены!'
        ]);

    }

    /**
     * @Route("/untie", name="send untie", methods={"DELETE"})
     */
    public function untieTeacherStudent( Request $request, UserTeacherRepository $repo):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        // Извлекаем заголовок "Authorization"
        $authorizationHeader = $request->headers->get('Authorization');

        // Проверяем, существует ли заголовок
        if (!$authorizationHeader) {
            return $this->json([
                'message' => 'Заголовок Authorization не предоставлен',
            ], 401);
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
            return $this->json([
                'message' => 'Доступ запрещен, требуется роль учителя или директора',
            ], 403);
        }

        $userTeacher = $repo->findOneBy([
            'user_id' => $data['id'],
            'teacher_id' => $usrData['data']['id'],
            ]);

        if (!$userTeacher instanceof userTeacher) {
            return $this->json([
                'message' => 'Связь не найдена',
            ], 404);
        }
        $repo->remove($userTeacher, true);
        return $this->json([
            'message' => 'Связь успешно удалена!',
        ]);


    }
}
