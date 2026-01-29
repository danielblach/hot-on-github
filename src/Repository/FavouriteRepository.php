<?php

namespace App\Repository;

use Psr\Cache\CacheItemPoolInterface;

class FavouriteRepository
{
    private const CACHE_KEY = 'favourites';

    public function __construct(
        private CacheItemPoolInterface $cache
    ) {}

    public function add(int $repoId): void
    {
        $item = $this->cache->getItem(self::CACHE_KEY);
        $data = $item->isHit() ? $item->get() : [];

        $data[$repoId] = true;

        $item->set($data);
        $this->cache->save($item);
    }

    public function remove(int $repoId): void
    {
        $item = $this->cache->getItem(self::CACHE_KEY);
        if (!$item->isHit()) {
            return;
        }

        $data = $item->get();
        unset($data[$repoId]);

        $item->set($data);
        $this->cache->save($item);
    }

    public function all(): array
    {
        $item = $this->cache->getItem(self::CACHE_KEY);
        return $item->isHit() ? array_keys($item->get()) : [];
    }

    public function isFavourite(int $repoId): bool
    {
        $item = $this->cache->getItem(self::CACHE_KEY);
        if (!$item->isHit()) {
            return false;
        }

        $data = $item->get();
        return isset($data[$repoId]);
    }
}
