<?php

namespace App\Tests;

use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\UserBookListRepository;
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
                    'apiCode' => 'FzVjBgAAQBAJ'
                ]
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteToList(): void
    {
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $user = $userRepository->find(1);
        $token = $this->login();
        $bookRepository = $this->getContainer()->get(BookRepository::class);
        $bookListRepository = $this->getContainer()->get(UserBookListRepository::class);
        $book = $bookRepository->findByApiCode('FzVjBgAAQBAJ');
        $bookListToDelete = $bookListRepository->searchByUserAndBook($user, $book);
        $response = static::createClient()->request('DELETE', '/api/list/books/' . $bookListToDelete->getId(), [
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
                'book'=>[
                    'test' => 'test'
                ]
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
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
                    'apiCode' => '1'
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
        $user = $userRepository->find(1);
        $token = $tokenInterface->create($user);
        return $token;
    }
}
