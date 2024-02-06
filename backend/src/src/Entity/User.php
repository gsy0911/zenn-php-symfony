<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use App\State\UserProvider;
use App\State\UserProcessor;
use App\State\UserBookProcessor;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use DateTimeImmutable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "user", options: ["comment" => "利用者テーブル"])]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ["groups" => ["user:get"]],
            denormalizationContext: ["groups" => ["user:get"]],
            provider: UserProvider::class,
        ),
        new GetCollection(
            provider: UserProvider::class
        ),
        new Post(
            normalizationContext: ["groups" => ["user:get"]],
            denormalizationContext: ["groups" => ["user:post"]]
        ),
        new Patch(
            normalizationContext: ["groups" => ["user:get"]],
            denormalizationContext: ["groups" => ["user:patch"]],
            provider: UserProvider::class,
            processor: UserProcessor::class,
        ),
        new Delete(),
    ],
    normalizationContext: ["groups" => ["user:get"]]
)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: "/users/{id}/books",
            uriVariables: [
                "id" => new Link(fromClass: User::class),
            ],
            denormalizationContext: ["groups" => ["user-book:post"]],
//            provider: UserProvider::class,
            processor: UserBookProcessor::class,
        ),
        new Delete(
            uriTemplate: "/users/{id}/books/{bookId}",
            uriVariables: [
                "id" => new Link(fromClass: User::class),
                "bookId" => new Link(fromClass: Book::class),
            ],
            denormalizationContext: ["groups" => ["user-book:delete"]],
//            provider: UserProvider::class,
//            processor: UserBookProcessor::class,

        ),
    ],
    normalizationContext: ["groups" => ["user:get"]],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ["user:get", "user:patch"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "名前を指定してください")]
    #[Groups(groups: ["user:get", "user:post", "user:patch"])]
    private ?string $name = null;

    /**
     * @var Collection<Book>
     */
    #[Groups(groups: ["user:get", "user-book:post"])]
    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "users")]
    private Collection $books;

    #[ORM\ManyToOne]
    #[Assert\NotBlank(message: "都道府県を選択してください")]
    #[Groups(groups: ["user:get", "user:post", "user:patch"])]
    private ?Prefecture $prefecture = null;

    #[ORM\Column(nullable: true, options: ["comment" => "削除日時"])]
    private ?DateTimeImmutable $deletedAt = null;

    #[Groups(groups: ["user:get"])]
    #[ORM\Column(updatable: false, options: [ "comment" => "作成日時" ])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => "Y-m-d"])]
    #[Gedmo\Timestampable(on: "create")]
    private ?DateTimeImmutable $createdAt = null;

    #[Groups(groups: ["user:get"])]
    #[ORM\Column(options: [ "comment" => "更新日時" ])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => "Y-m-d"])]
    #[Gedmo\Timestampable(on: "update")]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        // @see: https://www.doctrine-project.org/projects/doctrine-orm/en/2.17/reference/working-with-associations.html
        // @see: https://symfonycasts.com/screencast/collections/many-to-many-inverse
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /** @return Collection<Book> */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param Collection<Book> $book
     */
    public function setBooks(Collection $book): static
    {
        $this->books = $book;
        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->books->removeElement($book);

        return $this;
    }

    public function getPrefecture(): ?Prefecture
    {
        return $this->prefecture;
    }

    public function setPrefecture(?Prefecture $prefecture): static
    {
        $this->prefecture = $prefecture;
        return $this;
    }

}
