<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Util\ArticleUtility;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleService
{
    private ArticleRepository $articleRepository;
    private ValidatorInterface $validator;

    public function __construct(ArticleRepository $articleRepository, ValidatorInterface $validator) {
        $this->articleRepository = $articleRepository;
        $this->validator = $validator;
    }

    public function create(Article $article): void {
        try {
            $errors = $this->validator->validate($article);
            $this->validate($errors);

            $this->articleRepository->create($article);
        } catch (\Exception $exception) {
            if ($exception instanceof HttpException) {
                throw new HttpException($exception->getStatusCode(), $exception->getMessage());
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Article creation failed - {$exception->getMessage()}");
        }
    }

    public function all(int $limit, int $offset): array {
//        throw new \Exception("Not implemented", Response::HTTP_NOT_IMPLEMENTED);
        return $this->articleRepository->getAll($limit, $offset);
    }

    public function getById(int $id): Article {
//        throw new \Exception("Not implemented", Response::HTTP_NOT_IMPLEMENTED);
        return $this->articleRepository->getById($id);
    }

    public function update(Article $article): void {
        try {
            $errors = $this->validator->validate($article);
            $this->validate($errors);

            $this->articleRepository->update($article);
        } catch (\Exception $exception) {
            if ($exception instanceof HttpException) {
                throw new HttpException($exception->getStatusCode(), $exception->getMessage());
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, "Article update failed - {$exception->getMessage()}");
        }
    }

    public function delete(string $id): void {
        $this->articleRepository->delete((int)$id);
    }

    private function validate(ConstraintViolationListInterface $errors): void {
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            $errorMessage = implode(', ', $errorMessages);
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Validation failed - $errorMessage");
        }
    }
}