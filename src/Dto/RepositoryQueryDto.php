<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RepositoryQueryDto
{
    #[Assert\Choice(choices: [10, 50, 100], message: 'limit must be one of: 10, 50, 100')]
    public int $limit = 10;

    #[Assert\Regex(pattern: '/^\d{4}-\d{2}-\d{2}$/', message: 'createdFrom must be YYYY-MM-DD')]
    public string $createdFrom = '2019-01-10';

    #[Assert\Length(max: 30)]
    public ?string $language = null;
}
