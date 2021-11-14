<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\Movie;
use App\Entity\Music;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Generate several types of datas (Movies, Musics and Books) following his api code
 */
class DataGenerator
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Generate a Movie object by his api code
     *
     * @param string $apiCode the api code of the movie to generate
     * 
     * @return Movie|null
     */
    public function generateMovie(string $apiCode)
    {
        $response = $this->httpClient->request('GET', 'http://www.omdbapi.com/?i=' . $apiCode . '&apikey=' . $_ENV['OMDB_APIKEY']);
        $response = $response->toArray();
        if ($response['Response'] === 'False') {
            return null;
        }
        $movie = new Movie();
        $movie
            ->setTitle($response['Title'])
            ->setReleasedAt($response['Year'])
            ->setPictureUrl($response['Poster'])
            ->setApiCode($apiCode);
        return $movie;
    }

    /**
     * Generate a Music object by his api code
     *
     * @param string $apiCode the api code of the music to generate
     * 
     * @return Music|null
     */
    public function generateMusic(string $type, string $apiCode)
    {
        if (!in_array($type, ['Album', 'Song'])) {
            return null;
        }
        if ($type === 'Song') {
            $typeRequestApi = 'track';
        } else {
            $typeRequestApi = 'album';
        }
        $response = $this->httpClient->request('GET', 'https://api.deezer.com/' . $typeRequestApi . '/' . $apiCode);
        $response = $response->toArray();
        if (isset($response['error'])) {
            return null;
        }
        $music = new Music();
        $music
            ->setTitle($response['title'])
            ->setType($type)
            ->setArtist($response['artist']['name'])
            ->setPictureUrl($response['cover_xl'])
            ->setReleasedAt($response['release_date'])
            ->setApiCode($apiCode);
        return $music;
    }

    /**
     * Generate a Book object by his api code
     *
     * @param string $apiCode the api code of the book to generate
     * 
     * @return Book|null
     */
    public function generateBook(string $apiCode)
    {
        $response = $this->httpClient->request('GET', 'https://www.googleapis.com/books/v1/volumes/' . $apiCode);
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        $response = $response->toArray();
        $book = new Book();
        $book
            ->setApiCode($apiCode)
            ->setAuthor($response['volumeInfo']['authors'][0])
            ->setReleasedAt($response['volumeInfo']['publishedDate'])
            ->setTitle($response['volumeInfo']['title'])
            ->setPictureUrl($response['volumeInfo']['imageLinks']['large']);

        return $book;
    }
}
