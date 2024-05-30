<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    #[Route(['/', '/main'], name: 'main_index')]
    public function index(MainService $mainService): Response {
//        return $this->json($mainService->getHelloArray());
        return $this->render('main/hello.html.twig', [
            'message' => "HELLO"
        ]);
    }

    #[Route(['/hello'], name: 'main_hello')]
    public function hello(MainService $mainService): Response {
        return $this->render('main/hello.html.twig', [
            'message' => "HELLO"
        ]);
    }

    #[Route(['/madmin'], name: 'main_madmin')]
    public function madmin(): Response {
        // check access and redirect to login if unsuccessful
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /** @var User $user */
        $user = $this->getUser();


        if (in_array('ROLE_AUTHOR', $this->getUser()->getRoles(), true)) {
            xdebug_break();
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            xdebug_break();
        }
        return new Response("");
    }
}
