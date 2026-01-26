<?php

namespace App\Service;

use App\Client\GithubRepositorySearchClient;
use App\Dto\RepositoryDto;
use App\Dto\RepositoryListResponseDto;
use App\Repository\FavouriteRepository;
use Psr\Cache\CacheItemPoolInterface;

class RepositoryDiscoveryService
{
    public function __construct(
        private GithubRepositorySearchClient $githubClient,
        private FavouriteRepository $favourites,
        private CacheItemPoolInterface $cache,
        private int $cacheTtlSeconds,
    ) {}

    public function getPopularRepositories(int $limit, string $createdFrom, ?string $language): RepositoryListResponseDto
    {
        $cacheKey = sprintf('repos:%s:%s:%d',
            $createdFrom,
            $language ? strtolower($language) : 'any',
            $limit
        );

        $item = $this->cache->getItem($cacheKey);
        if (!$item->isHit()) {
            $data = $this->githubClient->search($createdFrom, $language, $limit);
            $item->set($data);
            $item->expiresAfter($this->cacheTtlSeconds);
            $this->cache->save($item);
        }

        $data = $item->get();
        $items = [];

        foreach (($data['items'] ?? []) as $repo) {
            $id = (int) $repo['id'];

            $items[] = new RepositoryDto(
                id: $id,
                fullName: (string) ($repo['full_name'] ?? ''),
                url: (string) ($repo['html_url'] ?? ''),
                description: $repo['description'] ?? null,
                language: $repo['language'] ?? null,
                stars: (int) ($repo['stargazers_count'] ?? 0),
                createdAt: (string) ($repo['created_at'] ?? ''),
                isFavourite: $this->favourites->isFavourite($id),
            );
        }

        return new RepositoryListResponseDto(
            limit: $limit,
            createdFrom: $createdFrom,
            language: $language,
            items: $items
        );
    }
}
