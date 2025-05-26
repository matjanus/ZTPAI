<?php

namespace App\Entity;

use App\Repository\AccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessRepository::class)]
class Access
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $accessName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccessName(): ?string
    {
        return $this->accessName;
    }

    public function setAccessName(string $accessName): static
    {
        $this->accessName = $accessName;

        return $this;
    }
}
