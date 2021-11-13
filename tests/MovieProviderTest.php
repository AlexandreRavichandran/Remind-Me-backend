<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class MovieProviderTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        $client = static::createClient()->request('GET', '/api/movies?q=Terminator');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame('200');
    }

    public function testGetItem(): void
    {
        $client = static::createClient()->request('GET', '/api/movies/tt0103064');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame('200');
        $this->assertJsonContains([
            "title" => "Terminator 2: Judgment Day",
            "category" => "Action, Sci-Fi",
            "realisator" => "James Cameron",
            "releasedAt" => "1991",
            "coverUrl" => "https://m.media-amazon.com/images/M/MV5BMGU2NzRmZjUtOGUxYS00ZjdjLWEwZWItY2NlM2JhNjkxNTFmXkEyXkFqcGdeQXVyNjU0OTQ0OTY@._V1_SX300.jpg",
            "synopsis" => "A cyborg, identical to the one who failed to kill Sarah Connor, must now protect her ten year old son, John Connor, from a more advanced and powerful cyborg.",
            "actors" => "Arnold Schwarzenegger, Linda Hamilton, Edward Furlong"
        ]);
    }

    public function testNotFound(): void
    {
        $client = static::createClient()->request('GET', '/api/movies/test');
        $this->assertResponseStatusCodeSame('404');
    }

    public function testBadRequest(): void
    {
        $client = static::createClient()->request('GET', '/api/movies');
        $this->assertResponseStatusCodeSame('400');
    }
}
