<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use App\State\UserProvider;
use App\State\UserBookProcessor;
use App\Repository\AuthorRepository;
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

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'user', options: ["comment" => '利用者テーブル'])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(provider: UserProvider::class),
        new Delete(),
    ]
)]
#[ApiResource(
    uriTemplate: "/users/books/{bookId}",
    operations: [
        new Post(
            denormalizationContext: ["group" => ["user-book:post"]],
            provider: UserProvider::class,
            processor: UserBookProcessor::class
        ),
        new Delete(provider: UserProvider::class),
    ],
    uriVariables: [
        'bookId' => new Link(fromClass: Book::class),
    ],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'post', 'patch'])]
    #[Assert\NotBlank(message: '名前を指定してください')]
    private ?string $name = null;

    /** @var Book[] */
    #[Groups(['get'])]
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "user")]
    private array|ArrayCollection $books;

    #[ORM\Column(nullable: true, options: ["comment" => '削除日時'])]
    private ?DateTimeImmutable $deletedAt = null;

    #[Groups(['get'])]
    #[ORM\Column(updatable: false, options: [ 'comment' => '作成日時' ])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt = null;

    #[Groups(['get'])]
    #[ORM\Column(options: [ 'comment' => '更新日時' ])]
    #[Gedmo\Timestampable(on: 'update')]
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

    /** @return Book[] */
    public function getBook(): array
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

    public function addBook(Book $book): static
    {
        $this->books[] = $book;
        return $this;
    }

}
