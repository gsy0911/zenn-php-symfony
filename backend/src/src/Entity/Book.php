<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use App\Repository\BookRepository;
use App\State\BookProvider;
use App\State\EmptyBookProvider;
use App\State\BookProcessor;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'book', options: ["comment" => '書籍テーブル'])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(denormalizationContext: ['groups' => ['book:post']]),
        new Patch(denormalizationContext: ['groups' => ['book:patch']], provider: BookProvider::class),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['book:get']],
)]
#[ApiResource(
    uriTemplate: "/authors/{authorId}/books",
    operations: [
        new GetCollection(
            openapiContext: [
                'summary' => "Retrieves the collection of Book resource related to the {authorId}",
                'description' => "Retrieves the collection of Book resource related to the {authorId}",
            ]
        ),
        new Post(
            openapiContext: [
                'summary' => "Creates a Book resource related to the {authorId}",
                'description' => "Creates a Book resource related to the {authorId}",
            ],
            normalizationContext: ["group" => ["book:post"]],
            denormalizationContext: [
                "group" => ["book:post"]
            ],
            provider: EmptyBookProvider::class,
            processor: BookProcessor::class
        ),
    ],
    uriVariables: [
        'authorId' => new Link(toProperty: 'author', fromClass: Author::class),
    ],
    normalizationContext: ['groups' => ['book:get']],
)]
#[ApiResource(
    uriTemplate: "/authors/{authorId}/books/{bookId}",
    operations: [
        new Get(
            openapiContext: [
                'summary' => "Retrieves the collection of Book resource related to the {authorId}",
                'description' => "Retrieves the collection of Book resource related to the {authorId}",
            ]
        ),
        new Patch(
            openapiContext: [
                'summary' => "Update the Book resource related to the {authorId}",
                'description' => "Update the Book resource related to the {authorId}",
            ],
            denormalizationContext: [
                "group" => ["book:patch"]
            ],
        ),
    ],
    uriVariables: [
        'authorId' => new Link(toProperty: 'author', fromClass: Author::class),
        'bookId' => new Link(fromClass: Book::class),
    ],
    normalizationContext: ['groups' => ['book:get']],
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:get', 'book:post'])]
    #[Assert\NotBlank(message: 'ISBNを指定してください')]
    private ?string $isbn = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:get', 'book:post', 'book:patch'])]
    #[Assert\NotBlank(message: 'タイトルを指定してください')]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: Author::class)]
    private ?Author $author = null;

    #[ORM\Column(nullable: true, options: ["comment" => '削除日時'])]
    private ?\DateTimeImmutable $deletedAt = null;

    #[Groups(['book:get'])]
    #[ORM\Column(updatable: false, options: [ 'comment' => '作成日時' ])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['book:get'])]
    #[ORM\Column(options: [ 'comment' => '更新日時' ])]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;
        return $this;
    }


    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
