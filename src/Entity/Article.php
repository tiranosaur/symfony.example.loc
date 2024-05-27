<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 63, unique: true)]
    private string $title;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\Type(type: 'float')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThanOrEqual(value: 99.99)] // max value
    #[Assert\GreaterThanOrEqual(value: 0)] // min value
    private float $price;

    #[ORM\Column(type: Types::STRING, length: 63, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $content;

    public function jsonSerialize(): array {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'price' => $this->getPrice(),
            'author' => $this->getAuthor(),
            'content' => $this->getContent(),
        ];
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function getAuthor(): ?string {
        return $this->author;
    }

    public function setAuthor(?string $author): void {
        $this->author = $author;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }
}
