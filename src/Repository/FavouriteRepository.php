<?php

namespace App\Repository;

class FavouriteRepository
{
    /** @var array<int, bool> */
    private array $favourites = [];

    public function add(int $repoId): void
    {
        $this->favourites[$repoId] = true;
    }

    public function remove(int $repoId): void
    {
        unset($this->favourites[$repoId]);
    }

    public function isFavourite(int $repoId): bool
    {
        return isset($this->favourites[$repoId]);
    }

    /**
     * @return int[]
     */
    public function all(): array
    {
        return array_keys($this->favourites);
    }
}
