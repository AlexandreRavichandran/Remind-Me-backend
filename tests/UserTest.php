<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $user = $userRepository->find(1);
        $response = static::createClient()->request('POST', 'api/login_check', [
            'json' => [
                'username' => $user->getUserIdentifier(),
                'password' => 'demo'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesJsonSchema(['token']);
    }

    public function testRegister(): void
    {
        $response = static::createClient()->request('POST', '/api/users', [
            'json' => [
                'email' => 'test@test.com',
                'password' => 'demo',
                'pseudonym' => 'testAccount'
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->deleteUser();
    }

    public function testLoginFail(): void
    {
        $response = static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'test@gmail.com',
                'password' => 'wrongPassword'
            ]
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testUsernameAlreadyExists(): void
    {
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $existantUserEmail = $userRepository->find(1)->getEmail();
        $response = static::createClient()->request('POST', '/api/users', [
            'json' => [
                'email' => $existantUserEmail,
                'password' => 'demo',
                'pseudonym' => 'test'
            ]
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testRegisterBadRequest(): void
    {
        $response = static::createClient()->request('POST', '/api/users', [
            'json' => [
                'test' => 'test',
                'password' => 47
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testLoginBadRequest(): void
    {
        $response = static::createClient()->request('POST', '/api/login_check', [
            'json' => [
                'user' => 'test@gmail.com',
                'pass' => 'demo'
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }

    private function deleteUser(): void
    {
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $userToDelete = $userRepository->findOneByEmail('test@test.com');
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->remove($userToDelete);
        $em->flush();
    }
}
