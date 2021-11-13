<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class MusicProviderTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/musics/albums?q=temps mort');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);

        $response = static::createClient()->request('GET', '/api/musics/songs?q=temps mort');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetItem(): void
    {
        $response = static::createClient()->request('GET', '/api/musics/albums/73913112');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            "artist" => "Booba",
            "apiCode" => 73913112,
            "title" => "Temps mort",
            "category" => "Rap/Hip Hop",
        ]);

        $response = static::createClient()->request('GET', '/api/musics/songs/558787752');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            "apiCode" => 558787752,
            "title" => "Ecoute bien",
            "album" => "Temps mort",
            "artist" => "Booba",
            "previewUrl" => "https://cdns-preview-b.dzcdn.net/stream/c-bed89b57b512af2eda3c839c753ab120-7.mp3",
            "pictureUrl" => "https://e-cdns-images.dzcdn.net/images/cover/6155066c2ef574b4b3f49feb671d3cc8/1000x1000-000000-80-0-0.jpg",
        ]);
    }

    public function testNotFound(): void
    {
        $response = static::createClient()->request('GET', '/api/musics/albums/test');
        $this->assertResponseStatusCodeSame(404);

        $response = static::createClient()->request('GET', '/api/musics/songs/test');
        $this->assertResponseStatusCodeSame(404);
    }

    public function testBadRequest(): void
    {
        $response = static::createClient()->request('GET', '/api/musics/albums');
        $this->assertResponseStatusCodeSame(400);

        $response = static::createClient()->request('GET', '/api/musics/songs');
        $this->assertResponseStatusCodeSame(400);
    }
}
