<?php

namespace App\Tests\Entity;

use App\Entity\Article;
use App\Util\ArticleUtility;

class ArticleUtil
{
    public static function getArticle(): Article {
        return self::getArticles()[0];
    }

    public static function getArticles(): array {
        return [
            ArticleUtility::fromJson(json_encode(self::getArticlesData()[0])),
            ArticleUtility::fromJson(json_encode(self::getArticlesData()[1]))
        ];
    }

    public static function getArticlesData(): array {
        return [
            [
                'id' => null,
                'title' => 'New Article',
                'price' => 9.99,
                'author' => 'Author Name',
                'content' => 'This is the content of the article.',
            ], [
                'id' => null,
                'title' => 'Second Article',
                'price' => 1.99,
                'author' => 'Author Fake Name',
                'content' => 'Second articlecontent',
            ]
        ];
    }
}
