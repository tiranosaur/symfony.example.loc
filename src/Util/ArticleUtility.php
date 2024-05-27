<?php

namespace App\Util;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ArticleUtility
{
    public static function map(Article $new, Article $old): void {
        $old->setTitle($new->getTitle());
        $old->setPrice($new->getPrice());
        $old->setAuthor($new->getAuthor() ?? null);
        $old->setContent($new->getContent());
    }

    public static function fromJson(string $jsonString): Article {
        try {
            $data = json_decode($jsonString, true);
            $article = new Article();
            $article->setId($data['id'] ?? null);
            $article->setTitle($data['title'] ?? null);
            $article->setPrice($data['price'] ?? null);
            $article->setAuthor($data['author'] ?? null);
            $article->setContent($data['content'] ?? null);
            return $article;
        } catch (\Exception $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Article json validation failed - {$e->getMessage()}");
        }
    }
}