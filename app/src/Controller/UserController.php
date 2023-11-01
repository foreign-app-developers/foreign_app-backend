<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/register", name="user_register", methods={"POST"})
     */
    public function register(Request $request,UserRepository $repo): Response
    {
        $data = json_decode($request->getContent(), true);

        // Создание экземпляра сущности User
        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setRoles(['ROLE_DIRECTOR']);

        $repo->add($user, True);

        return $this->json([
            'data' => $user,
            'message' => 'Пользователь успешно зарегистрирован!',
        ]);
    }

    public function getUserById(int $id): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->json($user);
    }

    /**
     * @Route("/user/add", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        // Другие свойства пользователя

        $userRepository->save($user, true);

        // Возвращаем ответ в формате JSON
        return $this->json([
            'data' => $user,
            'message' => 'Пользователь успешно создан!',
        ]);
    }

    // Другие методы для создания, обновления и удаления пользователей...
}
