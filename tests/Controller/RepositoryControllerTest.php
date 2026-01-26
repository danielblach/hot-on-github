<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepositoryControllerTest extends WebTestCase
{
    public function testValidationErrorOnInvalidLimit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/repositories?limit=999');

        $this->assertResponseStatusCodeSame(400);
        $this->assertJson($client->getResponse()->getContent());
    }
}
