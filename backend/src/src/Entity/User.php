<?php

namespace App\Entity;

use ApiPlatform\Metadata\Link;
use App\State\UserProvider;
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

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user', options: ["comment" => '利用者テーブル'])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            normalizationContext: ["groups" => ["user:get"]],
            denormalizationContext: ["groups" => ["user:post"]]
        ),
        new Patch(
            normalizationContext: ["groups" => ["user:get"]],
            denormalizationContext: ["groups" => ["user:patch"]],
            provider: UserProvider::class,
        ),
        new Delete(),
    ],
    normalizationContext: ["groups" => ["user:get"]]
)]
#[ApiResource(
    uriTemplate: "/users/books/{bookId}",
    operations: [
        new Post(
            denormalizationContext: ["groups" => ["user-book:post"]],
            provider: UserProvider::class,
            processor: UserBookProcessor::class
        ),
        new Delete(provider: UserProvider::class, processor: UserBookProcessor::class),
    ],
    uriVariables: [
        'bookId' => new Link(fromClass: Book::class),
    ],
    normalizationContext: ["groups" => ["user:get"]],
)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['user:get', "user:patch"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:get', 'user:post', 'user:patch'])]
    #[Assert\NotBlank(message: '名前を指定してください')]
    private ?string $name = null;

    /**
     * @var Collection<Book>
     */
    #[Groups(groups: ["user:get"])]
    #[MaxDepth(1)]
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "users")]
    private Collection $books;

    #[ORM\Column(nullable: true, options: ["comment" => '削除日時'])]
    private ?DateTimeImmutable $deletedAt = null;

    #[Groups(['user:get'])]
    #[ORM\Column(updatable: false, options: [ 'comment' => '作成日時' ])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt = null;

    #[Groups(['user:get'])]
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

    public function addBooks(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }
        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->books->removeElement($book);

        return $this;
    }
}
