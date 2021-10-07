<?php

namespace App\DataProvider;

use App\Entity\MusicProvider;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;

class MusicDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    const API_COLLECTION_URL = 'https://itunes.apple.com/search?';
    const API_ITEM_URL = 'https://itunes.apple.com/lookup?';
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
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

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        $query = 'id=' . $id;
        $response = $this->executeApiRequest($query, null, false);

        $result = $response['results'][0];
        $type = $result['wrapperType'];
        $music = new MusicProvider();

        switch ($type) {
            case 'collection':
                $music
                    ->setType('Album')
                    ->setArtist($result['artistName'])
                    ->setTitle($result['collectionName'])
                    ->setReleasedAt($result['releaseDate'])
                    ->setApiCode($result['collectionId']);

                break;
            case 'track':
                $music
                    ->setType('Song')
                    ->setArtist($result['artistName'])
                    ->setTitle($result['trackName'])
                    ->setReleasedAt($result['releaseDate'])
                    ->setApiCode($result['trackId']);

                break;
            case 'artist':
                $music
                    ->setType('Artist')
                    ->setTitle($result['artistName'])
                    ->setApiCode($result['artistId']);

                break;
        }
        $music->setCategory($result['primaryGenreName']);

        return $music;
    }

    private function executeApiRequest(string $query, string $entity = null, $isCollection = true)
    {
        if ($isCollection) {
            $response = $this->client->request('GET', SELF::API_COLLECTION_URL . $query . '&' . $entity);
        } elseif (!$isCollection) {
            $response = $this->client->request('GET', SELF::API_ITEM_URL . $query);
        }
        return $response->toArray();
    }

    private function handleArtistRequest(string $query, string $entity)
    {
        $response = $this->executeApiRequest($query, $entity);
        $datas = [];
        foreach ($response['results'] as $artistData) {
            $artist = new MusicProvider;
            $artist
                ->setApiCode($artistData['artistId'])
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
                ->setApiCode($songData['trackId'])
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
                ->setApiCode($albumData['collectionId'])
                ->setCategory($albumData['primaryGenreName'])
                ->setTitle($albumData['collectionName'])
                ->setReleasedAt($albumData['releaseDate'])
                ->setArtist($albumData['artistName']);

            $datas[] = $album;
        }

        return $datas;
    }

}
