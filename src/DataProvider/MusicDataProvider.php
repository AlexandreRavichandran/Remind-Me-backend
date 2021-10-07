<?php

namespace App\DataProvider;

use App\Entity\MusicProvider;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;


class MusicDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    const API_URL = 'https://itunes.apple.com/search?';
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    private function executeApiRequest(string $query, string $entity)
    {
        $response = $this->client->request('GET', SELF::API_URL . $query . '&' . $entity);
        return $response->toArray();
    }

    private function handleArtistRequest(string $query, string $entity)
    {
        $response = $this->executeApiRequest($query, $entity);
        $datas = [];
        foreach ($response['results'] as $artistData) {
            $artist = new MusicProvider;
            $artist
                ->setApiId($artistData['artistId'])
                ->setTitle($artistData['artistName'])
                ->setType('Artist')
                ->setReleasedAt(null);
            $datas[] = $artist;
        }

        return $datas;
    }

    private function handleSongRequest(string $query, string $entity)
    {
        $response = $this->executeApiRequest($query, $entity);

        $datas = [];
        foreach ($response['results'] as $songData) {

            $song = new MusicProvider();
            $song
                ->setApiId($songData['trackId'])
                ->setType('Song')
                ->setCategory($songData['primaryGenreName'])
                ->setReleasedAt($songData['releaseDate'])
                ->setArtist($songData['artistName'])
                ->setTitle($songData['trackName']);
            $datas[] = $song;
        }

        return $datas;
    }
    private function handleAlbumRequest(string $query, string $entity)
    {
        $response = $this->executeApiRequest($query, $entity);
        $datas = [];

        foreach ($response['results'] as $albumData) {
            $album = new MusicProvider();
            $album
                ->setType('Album')
                ->setApiId($albumData['collectionId'])
                ->setCategory($albumData['primaryGenreName'])
                ->setTitle($albumData['collectionName'])
                ->setReleasedAt($albumData['releaseDate'])
                ->setArtist($albumData['artistName']);

            $datas[] = $album;
        }

        return $datas;
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return $resourceClass === MusicProvider::class;
    }
    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {
        $queryType = $context['filters']['type'];
        $query = $context['filters']['q'];

        $addQuery = 'term=' . $query;

        switch ($queryType) {
            case 'album':
                $addEntity = 'entity=album';
                $response = $this->handleAlbumRequest($addQuery, $addEntity);
                break;
            case 'artist':
                $addEntity = 'entity=musicArtist';
                $response = $this->handleArtistRequest($addQuery, $addEntity);
                break;
            case 'song':
                $addEntity = 'entity=song';
                $response = $this->handleSongRequest($addQuery, $addEntity);
                break;
        }

        return $response;
    }
}
