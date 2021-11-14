<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\UserMovieListRepository;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\MovieRepository;
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
        $this->assertResponseIsSuccessful();
    }

    public function testAddToList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/movies', [
            'json' => [
                'movie' => [
                    'apiCode' => 'tt1037705',
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
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $user = $userRepository->find(1);
        $token = $this->login();
        $movieRepository = $this->getContainer()->get(MovieRepository::class);
        $movieListRepository = $this->getContainer()->get(UserMovieListRepository::class);
        $movie = $movieRepository->findByApiCode('tt1037705');
        $movieListToDelete = $movieListRepository->searchByUserAndMovie($user, $movie);
        $response = static::createClient()->request('DELETE', '/api/list/movies/' . $movieListToDelete->getId(), [
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
                    'apiCode' => '1'
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
        $user = $userRepository->find(1);
        $token = $tokenInterface->create($user);
        return $token;
    }
}
