<?php

namespace App\Tests;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserBookListTest extends ApiTestCase
{
    public function testGetList(): void
    {
        $token = $this->login();

        $response = static::createClient()->request('GET', '/api/list/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $this->assertResponseIsSuccessful(200);
    }

    public function testAddList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'book' => [
                    'apiCode' => 'EZtWvgAACAAJ'
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteToList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/books', [
            'json' => [
                'book' => [
                    'apiCode' => '1437135238',
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $bookRepository = $this->getContainer()->get(BookRepository::class);
        $bookToDelete = $bookRepository->findByApiCode(1437135238);
        $response = static::createClient()->request('DELETE', '/api/list/books/' . $bookToDelete->getId(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);
    }

    public function testAddBadRequest(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'test' => 'test'
            ]
        ]);
        $this->assertResponseStatusCodeSame(422);
    }

    public function testUnauthorized(): void
    {
        $response = static::createClient()->request('GET', '/api/list/books');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAddNotFound(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'book' => [
                    'apiCode' => 1
                ]
            ]
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeleteNotFound(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('DELETE', '/api/list/books/test', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    private function login(): string
    {
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $tokenInterface = $this->getContainer()->get(JWTTokenManagerInterface::class);
        $users = $userRepository->findAll();
        $randomUser = $users[array_rand($users)];
        $token = $tokenInterface->create($randomUser);
        return $token;
    }
}
