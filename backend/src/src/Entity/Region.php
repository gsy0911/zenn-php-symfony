<?php

namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'region', options: ["comment" => '地域区分テーブル'])]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ["groups" => ["region:get"]]),
    ],
)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(groups: ['prefecture:get', "region:get"])]
    #[ORM\Column(length: 8, options: ["comment" => '地域区分名'])]
    private ?string $name = null;

    #[Groups(groups: ["region:get"])]
    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Prefecture::class)]
    private Collection $prefectures;

    public function __construct()
    {
        $this->prefectures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Prefecture>
     */
    public function getPrefectures(): Collection
    {
        return $this->prefectures;
    }

    public function addPrefecture(Prefecture $prefecture): static
    {
        if (!$this->prefectures->contains($prefecture)) {
            $this->prefectures->add($prefecture);
            $prefecture->setRegion($this);
        }

        return $this;
    }

    public function removePrefecture(Prefecture $prefecture): static
    {
        if ($this->prefectures->removeElement($prefecture)) {
            // set the owning side to null (unless already changed)
            if ($prefecture->getRegion() === $this) {
                $prefecture->setRegion(null);
            }
        }

        return $this;
    }
}
