<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tasks")
 */
class TaskController extends AbstractController
{

    public function getTaskById(int $id): JsonResponse
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->find($id);

        return $this->json($task);
    }

    // Другие методы
}
