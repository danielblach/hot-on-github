<?php

namespace App\Dto;

class RepositoryDto
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $url,
        public ?string $description,
        public ?string $language,
        public int $stars,
        public string $createdAt,
        public bool $isFavourite,
    ) {}
}
