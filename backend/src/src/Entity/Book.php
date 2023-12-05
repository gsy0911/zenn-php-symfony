<?php

namespace App\Entity;

use App\Repository\BookRepository;
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

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(denormalizationContext: ['groups' => ['post']]),
        new Patch(denormalizationContext: ['groups' => ['patch']]),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['get']],
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get'])]
    #[SerializedName("number")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'post', 'patch'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'post', 'patch'])]
    private ?string $author = null;

    #[Groups(['get'])]
    #[ORM\Column(updatable: false, options: [ 'comment' => '作成日時' ])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    #[Groups(['get'])]
    #[ORM\Column(options: [ 'comment' => '更新日時' ])]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
