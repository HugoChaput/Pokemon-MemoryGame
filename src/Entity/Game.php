<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Pokemon::class, inversedBy: 'games')]
    private Collection $pokemons;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameOptions::class)]
    private Collection $gameOptions;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: GameUser::class, orphanRemoval: true)]
    private Collection $gameUsers;

   
    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->gameOptions = new ArrayCollection();
        $this->gameUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        $this->pokemons->removeElement($pokemon);

        return $this;
    }

    /**
     * @return Collection<int, GameOptions>
     */
    public function getGameOptions(): Collection
    {
        return $this->gameOptions;
    }

    public function addGameOption(GameOptions $gameOption): self
    {
        if (!$this->gameOptions->contains($gameOption)) {
            $this->gameOptions->add($gameOption);
            $gameOption->setGame($this);
        }

        return $this;
    }

    public function removeGameOption(GameOptions $gameOption): self
    {
        if ($this->gameOptions->removeElement($gameOption)) {
            // set the owning side to null (unless already changed)
            if ($gameOption->getGame() === $this) {
                $gameOption->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GameUser>
     */
    public function getGameUsers(): Collection
    {
        return $this->gameUsers;
    }

    public function addGameUser(GameUser $gameUser): self
    {
        if (!$this->gameUsers->contains($gameUser)) {
            $this->gameUsers->add($gameUser);
            $gameUser->setGame($this);
        }

        return $this;
    }

    public function removeGameUser(GameUser $gameUser): self
    {
        if ($this->gameUsers->removeElement($gameUser)) {
            // set the owning side to null (unless already changed)
            if ($gameUser->getGame() === $this) {
                $gameUser->setGame(null);
            }
        }

        return $this;
    }
}
