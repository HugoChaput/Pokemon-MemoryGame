<?php

namespace App\Entity;

use App\Repository\GameOptionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameOptionsRepository::class)]
class GameOptions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $options = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isOptions(): ?bool
    {
        return $this->options;
    }

    public function setOptions(bool $options): self
    {
        $this->options = $options;

        return $this;
    }
}
