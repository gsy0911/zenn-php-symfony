<?php

namespace App\Entity;

use App\Repository\BookRepository;
use App\State\BookProvider;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
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
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['book:get', 'book:post', "user:get"])]
    #[Assert\NotBlank(message: 'ISBNを指定してください')]
    private string $isbn;

    #[ORM\Column(length: 255)]
    #[Groups(['book:get', 'book:post', 'book:patch', "user:get"])]
    #[Assert\NotBlank(message: 'タイトルを指定してください')]
    private string $title;

    /** @var Collection<User> */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: "books")]
    private Collection $users;

    #[ORM\Column(nullable: true, options: ["comment" => '削除日時'])]
    private ?DateTimeImmutable $deletedAt = null;

    #[Groups(['book:get', "user:get"])]
    #[ORM\Column(updatable: false, options: [ 'comment' => '作成日時' ])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt = null;

    #[Groups(['book:get', "user:get"])]
    #[ORM\Column(options: [ 'comment' => '更新日時' ])]
    #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        // @see: https://www.doctrine-project.org/projects/doctrine-orm/en/2.17/reference/working-with-associations.html
        // @see: https://symfonycasts.com/screencast/collections/many-to-many-inverse
        $this->users = new ArrayCollection();
    }


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

    /** @return Collection<User> */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }


    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
