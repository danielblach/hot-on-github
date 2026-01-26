<?php

namespace App\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GithubRepositorySearchClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $baseUrl,
        private ?string $token,
        private int $timeoutSeconds,
    ) {}
    
    public function search(string $createdFrom, ?string $language, int $limit): array
    {
        $qParts = ["created:>$createdFrom"];
        if ($language) {
            $qParts[] = "language:" . $language;
        }

        $query = [
            'q' => implode(' ', $qParts),
            'sort' => 'stars',
            'order' => 'desc',
            'per_page' => $limit,
            'page' => 1,
        ];

        $headers = [
            'Accept' => 'application/vnd.github+json',
            'User-Agent' => 'backend-demo-symfony',
        ];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        $response = $this->httpClient->request('GET', rtrim($this->baseUrl, '/') . '/search/repositories', [
            'query' => $query,
            'headers' => $headers,
            'timeout' => $this->timeoutSeconds,
        ]);

        return $response->toArray(false);
    }
}
