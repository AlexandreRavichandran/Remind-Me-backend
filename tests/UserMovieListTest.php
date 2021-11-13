<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserMovieListTest extends ApiTestCase
{
    public function testGetList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/list/movies', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $this->assertResponseIsSuccessful(200);
    }

    public function testAddToList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/movies', [
            'json' => [
                'movie' => [
                    'apiCode' => '1437135238',
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
    }

    public function testDeleteToList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/movies', [
            'json' => [
                'movie' => [
                    'apiCode' => '1437135238',
                ]
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $movieRepository = $this->getContainer()->get(MovieRepository::class);
        $movieToDelete = $movieRepository->findByApiCode(1437135238);
        $response = static::createClient()->request('DELETE', '/api/list/movies/' . $movieToDelete->getId(), [
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
        $response = static::createClient()->request('POST', '/api/list/movies', [
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
        $response = static::createClient()->request('GET', '/api/list/movies');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAddNotFound(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/movies', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'movie' => [
                    'apiCode' => 1
                ]
            ]
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeleteNotFound(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('DELETE', '/api/list/movies/test', [
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
