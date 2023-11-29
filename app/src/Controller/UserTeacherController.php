<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Repository\UserTeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\UserTeacher;

/**
 * @Route("/api")
 */
class UserTeacherController extends AbstractController
{
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
        $repo->save($invent, True);

        //возвращаем ответ в формате json
        return $this->json([
            'data' => $invent,
            'message' => 'Приглашение успешно создано!',
        ]);
    }
    /**
     * @Route("/getStudents", name="get_students_from_teacher", methods={"GET"})
     */
    public function getStudents(Request $request, UserTeacherRepository $repo): JsonResponse
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
        if (!((in_array('ROLE_TEACHER' , $usrData['data']['roles'])) or (in_array('ROLE_DIRECTOR' , $usrData['data']['roles']))))  {
            return $this->json([
                'message' => 'Доступ запрещен, требуется роль учителя или директора',
            ], 403);
        }
        $students = $repo->findBy(['teacher_id' => $usrData['data']['id']]);
        return $this->json([
            'data'=> $students,
            'massage'=> 'Студенты получены!'
        ]);
    }
}
