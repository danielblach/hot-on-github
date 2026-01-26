<?php

namespace App\Dto;

class RepositoryListResponseDto
{
    /**
     * @param RepositoryDto[] $items
     */
    public function __construct(
        public int $limit,
        public string $createdFrom,
        public ?string $language,
        public array $items
    ) {}
}
