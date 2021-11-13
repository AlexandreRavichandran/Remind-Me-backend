<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class BookProviderTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/books?q=Harry potter');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetItem(): void
    {
        $response = static::createClient()->request('GET', '/api/books/EZtWvgAACAAJ');
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseIsSuccessful();
    }

    public function testNotFound(): void
    {
        $response = static::createClient()->request('GET', '/api/books/test');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testBadRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/books');
        $this->assertResponseStatusCodeSame(400);
    }
}
