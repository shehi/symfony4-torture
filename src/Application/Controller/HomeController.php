<?php

declare(strict_types=1);

namespace App\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->json(['message' => 'OK']);
    }
}
