<?php

namespace App\Tests\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Tests\Entity\ArticleUtil;
use App\Util\ArticleUtility;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ArticleControllerTest extends WebTestCase
{
    private MockObject $articleRepositoryMock;

    protected function setUp(): void {
        parent::setUp();
        $this->articleRepositoryMock = $this->createMock(ArticleRepository::class);
    }

    public function test_view(): void {
        $client = static::createClient();

        $client->request('GET', '/article');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('<html', $client->getResponse()->getContent());
        $this->assertStringContainsString('article_title', $client->getResponse()->getContent());
        $this->assertStringContainsString('article_price', $client->getResponse()->getContent());
        $this->assertStringContainsString('article_content', $client->getResponse()->getContent());
    }

    public function test_create(): void {
        $client = static::createClient();
        $client->getContainer()->set(ArticleRepository::class, $this->articleRepositoryMock);

        $article = ArticleUtil::getArticle();

        $this->articleRepositoryMock
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function (Article $a) use ($article) {
                return $a->getTitle() === $article->getTitle() &&
                    $a->getPrice() === $article->getPrice() &&
                    $a->getAuthor() === $article->getAuthor() &&
                    $a->getContent() === $article->getContent();
            }));

        $client->request('POST', '/article', [], [], [], json_encode($article));

        $this->assertSame(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertSame('Article created', $client->getResponse()->getContent());
    }

    public function test_index(): void {
        $client = static::createClient();
        $client->getContainer()->set(ArticleRepository::class, $this->articleRepositoryMock);

        $articles = ArticleUtil::getArticles();

        $this->articleRepositoryMock
            ->expects($this->once())
            ->method('getAll')
            ->with(10, 0)
            ->willReturn($articles);

        $client->request('GET', '/article/all/10/0');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($articles), $client->getResponse()->getContent());
    }

    public function test_getById(): void {
        $client = static::createClient();
        $client->getContainer()->set(ArticleRepository::class, $this->articleRepositoryMock);

        $article = ArticleUtil::getArticle();

        $this->articleRepositoryMock
            ->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($article);

        $client->request('GET', '/article/1');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($article), $client->getResponse()->getContent());
    }

    public function test_update(): void {
        $client = static::createClient();
        $client->getContainer()->set(ArticleRepository::class, $this->articleRepositoryMock);

        $article = ArticleUtil::getArticle();

        $this->articleRepositoryMock
            ->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($article);

        $client->request('GET', '/article/1');

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode($article), $client->getResponse()->getContent());
    }


    public function testUpdate(): void {
        $client = static::createClient();

        $client->getContainer()->set(ArticleRepository::class, $this->articleRepositoryMock);

        $article = ArticleUtil::getArticle();

        $this->articleRepositoryMock
            ->expects($this->once())
            ->method('update')
            ->with($this->callback(function (Article $a) use ($article) {
                return $a->getId() === $article->getId() &&
                    $a->getTitle() === $article->getTitle() &&
                    $a->getPrice() === $article->getPrice() &&
                    $a->getAuthor() === $article->getAuthor() &&
                    $a->getContent() === $article->getContent();
            }));

        $client->request('PUT', '/article', [], [], [], json_encode($article));

        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSame('Article updated', $client->getResponse()->getContent());
    }
}
