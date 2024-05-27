<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\ArticleService;
use App\Util\ArticleUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private ArticleService $articleService;

    public function __construct(ArticleService $articleService) {
        $this->articleService = $articleService;
    }

    #[Route(['/article'], name: 'article_view', methods: ['GET'])]
    public function view(): Response {
        return $this->render("article/view.html.twig", []);
    }

    #[Route('/article', name: 'article_create', methods: ['POST'])]
    public function create(Request $request,): Response {
        $article = ArticleUtility::fromJson($request->getContent());
        $this->articleService->create($article);
        return new Response('Article created', Response::HTTP_CREATED);
    }

    #[Route(['/article/all/{limit}/{offset}'], name: 'article_all', methods: ['GET'])]
    public function index(int $limit, int $offset): Response {
        return $this->json($this->articleService->all($limit, $offset));
    }

    #[Route('/article/{id}', name: 'article_by_id', methods: ['GET'])]
    public function getById(string $id): Response {
        return $this->json($this->articleService->getById((int)$id));
    }

    #[Route('/article', name: 'article_update', methods: ['PUT'])]
    public function update(Request $request): Response {
        $article = ArticleUtility::fromJson($request->getContent());
        $this->articleService->update($article);
        return new Response('Article updated', Response::HTTP_OK);
    }

    #[Route('/article/{id}', name: 'article_delete', methods: ['DELETE'])]
    public function delete(string $id): Response {
        $this->articleService->delete($id);
        return new Response('Article deleted', Response::HTTP_OK);
    }
}
