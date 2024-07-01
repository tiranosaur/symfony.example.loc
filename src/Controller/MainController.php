<?php

namespace App\Controller;

use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    #[Route(['/'], name: 'main_index')]
    public function index(): Response
    {
        return $this->render("main/index.html.twig", [
            "title" => "Main Page"
        ]);
    }

    #[Route(['/hello'], name: 'main_hello')]
    public function hello(MainService $mainService): Response
    {
        return new Response($mainService->getHello());
    }

    #[Route(['/json'], name: 'main_json')]
    public function testJson(MainService $mainService): JsonResponse
    {
        return $this->json($mainService->getHelloArray());
    }
}
