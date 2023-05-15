<?php

namespace App\Controller;

use App\Entity\Material;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/materials")
 */
class MaterialController extends AbstractController
{

    public function getMaterialById(int $id): JsonResponse
    {
        $material = $this->getDoctrine()->getRepository(Material::class)->find($id);

        return $this->json($material);
    }

    // Другие методы
}
