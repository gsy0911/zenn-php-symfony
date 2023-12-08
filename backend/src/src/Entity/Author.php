<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'author', options: ["comment" => '著者テーブル'])]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ApiResource]
class Author
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
//    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "author")]
//    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[Link(toProperty: 'author')]
    private $books = [];

    #[ORM\Column(nullable: true, options: ["comment" => '削除日時'])]
    private ?\DateTimeImmutable $deletedAt = null;

    #[Groups(['get'])]
    #[ORM\Column(updatable: false, options: [ 'comment' => '作成日時' ])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(['get'])]
    #[ORM\Column(options: [ 'comment' => '更新日時' ])]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getBook()
    {
        return $this->books;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

}
