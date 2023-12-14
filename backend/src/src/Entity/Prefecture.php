<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PrefectureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrefectureRepository::class)]
#[ORM\Table(name: 'prefecture', options: ["comment" => '都道府県テーブル'])]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ["groups" => ["prefecture:get"]]),
    ],
)]
class Prefecture
{
    #[Groups(['prefecture'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(groups: ['prefecture:get'])]
    #[ORM\ManyToOne(inversedBy: 'prefectures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    #[Groups(groups: ['prefecture:get', "region:get"])]
    #[ORM\Column(length: 8, options: ["comment" => '都道府県名'])]
    private ?string $name = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
