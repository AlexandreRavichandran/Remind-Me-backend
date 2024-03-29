<?php

namespace App\Tests;

use App\Repository\UserRepository;
use App\Repository\MusicRepository;
use App\Repository\UserMusicListRepository;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserMusicListTest extends ApiTestCase
{
    public function testGetList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('GET', '/api/list/musics', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $this->assertResponseIsSuccessful(200);
    }

    public function testAddToList(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/musics', [
            'json' => [
                'music' => [
                    'apiCode' => '73913112',
                    'type'=>'Album'
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
        $musicRepository = $this->getContainer()->get(MusicRepository::class);
        $musicListRepository = $this->getContainer()->get(UserMusicListRepository::class);
        $music = $musicRepository->findByApiCode('73913112');
        $musicListToDelete = $musicListRepository->searchByUserAndMusic($user, $music);
        $response = static::createClient()->request('DELETE', '/api/list/musics/' . $musicListToDelete->getId(), [
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
        $response = static::createClient()->request('POST', '/api/list/musics', [
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
        $response = static::createClient()->request('GET', '/api/list/musics');
        $this->assertResponseStatusCodeSame(401);
    }

    public function testAddNotFound(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('POST', '/api/list/musics', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'music' => [
                    'apiCode' => '1',
                    'type'=>'Album'
                ]
            ]
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDeleteNotFound(): void
    {
        $token = $this->login();
        $response = static::createClient()->request('DELETE', '/api/list/musics/test', [
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
