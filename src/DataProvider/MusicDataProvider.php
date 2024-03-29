<?php

namespace App\DataProvider;

use Exception;
use App\Entity\MusicSongProvider;
use App\Entity\MusicAlbumProvider;
use App\Entity\MusicArtistProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class MusicDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    const API_COLLECTION_URL = 'https://api.deezer.com/search?';
    const API_ITEM_URL = 'https://api.deezer.com/';
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return ($resourceClass === MusicSongProvider::class) || ($resourceClass === MusicArtistProvider::class) || ($resourceClass === MusicAlbumProvider::class);
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {
        //Check if a query exists
        try {
            $query = $context['filters']['q'];
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => 'You must add a query "q" on your request'], Response::HTTP_BAD_REQUEST);
        }

        switch ($resourceClass) {
            case 'App\Entity\MusicAlbumProvider':
                $addEntity = 'album:';
                $response = $this->handleAlbumRequest($query);
                break;
            case 'App\Entity\MusicArtistProvider':
                $addEntity = 'artist:';
                $response = $this->handleArtistRequest($query);
                break;
            case 'App\Entity\MusicSongProvider':
                $addEntity = 'track:';
                $response = $this->handleSongRequest($query);
                break;
        }

        return $response;
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        $type = null;
        switch ($resourceClass) {
            case 'App\Entity\MusicAlbumProvider':
                $requestUrl = 'album/';
                $type = 'album';
                break;
            case 'App\Entity\MusicArtistProvider':
                $requestUrl = 'artist/';
                $type = 'artist';
                break;
            case 'App\Entity\MusicSongProvider':
                $requestUrl = 'track/';
                $type = 'song';
                break;
        }
        $requestUrl = $requestUrl . $id;
        $response = $this->executeApiRequest($requestUrl, null, false);
        if (!isset($response['error'])) {

            switch ($type) {
                case 'album':
                    $music = new MusicAlbumProvider;
                    $music
                        ->setArtist($response['artist']['name'])
                        ->setTitle($response['title'])
                        ->setReleasedAt(substr($response['release_date'], 0, 4))
                        ->setApiCode($response['id'])
                        ->setCategory($response['genres']['data'][0]['name'])
                        ->setPictureUrl($response['cover_xl'])
                        ->setArtistApiCode($response['artist']['id']);

                    $tracklist = [];
                    foreach ($response['tracks']['data'] as $track) {
                        $tracklist[] = [
                            'apiCode' => $track['id'],
                            'title' => $track['title']
                        ];
                    }
                    $music->setTracklist($tracklist);

                    break;
                case 'song':
                    $music = new MusicSongProvider;
                    $music
                        ->setArtist($response['artist']['name'])
                        ->setTitle($response['title'])
                        ->setReleasedAt(substr($response['release_date'], 0, 4))
                        ->setApiCode($response['id'])
                        ->setPreviewUrl($response['preview'])
                        ->setPictureUrl($response['album']['cover_xl'])
                        ->setAlbum($response['album']['title'])
                        ->setAlbumApiCode($response['album']['id'])
                        ->setArtistPictureUrl($response['artist']['picture_xl'])
                        ->setArtistApiCode($response['artist']['id']);
                    break;
                case 'artist':
                    $music = new MusicArtistProvider;
                    $music
                        ->setName($response['name'])
                        ->setApiCode($response['id'])
                        ->setPictureUrl($response['picture_xl']);

                    break;
            }
        } else {
            return new JsonResponse([], 404);
        }
        return $music;
    }

    /**
     * Executes the request to deezer API
     * 
     * @param string $query the query to research, might be an APi code or a simple query
     * @param string $entity the type of element to search. 3 possible values : 'album:', 'artist:', 'track:' 
     * @param boolean $isCollection True if a group of books are searched, false if you search only one book
     * 
     * @return array
     */
    private function executeApiRequest(string $query, string $entity = null, $isCollection = true)
    {
        if ($isCollection) {
            $response = $this->client->request('GET', SELF::API_COLLECTION_URL . 'q=' . $entity . '"' . $query . '"');
        } elseif (!$isCollection) {
            $response = $this->client->request('GET', SELF::API_ITEM_URL . $query);
        }

        return $response->toArray();
    }

    /**
     * Create a artist object following the request
     *
     * @param string $query The query of the artist
     * 
     * @return array $datas Response of the query
     */
    private function handleArtistRequest(string $query): array
    {
        $response = $this->executeApiRequest($query, 'artist:');
        $datas = [];
        $exists = false;
        if ($response['total'] > 0) {
            foreach ($response['data'] as $artistData) {
                $artist = new MusicArtistProvider;
                $artist
                    ->setApiCode($artistData['artist']['id'])
                    ->setName($artistData['artist']['name'])
                    ->setPictureUrl($artistData['artist']['picture_xl']);

                /**
                 * Verification to check if an artist is already on the result array.
                 * Useful to prevent duplicate artists ont the response array.
                 * If the artist is not already on the response array, add it.
                 */
                foreach ($datas as $data) {
                    if ($artist->getApiCode() === $data->getApiCode()) {
                        $exists = true;
                    }
                }
                if (!$exists) {
                    $datas[] = $artist;
                }
            }
        }

        return $datas;
    }

    /**
     * Create a song object following the request
     *
     * @param string $query The query of the song
     * 
     * @return array $datas Response of the query
     */
    private function handleSongRequest(string $query)
    {
        $response = $this->executeApiRequest($query, 'track:');
        $datas = [];
        $exists = false;
        if ($response['total'] > 0) {
            foreach ($response['data'] as $songData) {
                $song = new MusicSongProvider();
                $song
                    ->setApiCode($songData['id'])
                    ->setArtist($songData['artist']['name'])
                    ->setTitle($songData['title'])
                    ->setAlbumApiCode($songData['album']['id'])
                    ->setArtistApiCode($songData['album']['id'])
                    ->setPictureUrl($songData['album']['cover_xl'])
                    ->setPreviewUrl($songData['preview']);
                $datas[] = $song;
            }
        }

        return $datas;
    }

    /**
     * Create a album object following the request
     *
     * @param string $query The query of the album
     * 
     * @return array $datas Response of the query
     */
    private function handleAlbumRequest(string $query)
    {
        $response = $this->executeApiRequest($query, 'album:');
        $datas = [];
        $exists = false;
        if ($response['total'] > 0) {
            foreach ($response['data'] as $albumData) {
                $album = new MusicAlbumProvider();
                $album
                    ->setApiCode($albumData['album']['id'])
                    ->setArtistApiCode($albumData['artist']['id'])
                    ->setTitle($albumData['album']['title'])
                    ->setArtist($albumData['artist']['name'])
                    ->setPictureUrl($albumData['album']['cover_xl']);

                /**
                 * Verification to check if an album is already on the result array.
                 * Useful to prevent duplicate albums ont the response array.
                 * If the album is not already on the response array, add it.
                 */
                foreach ($datas as $data) {
                    if ($album->getApiCode() === $data->getApiCode()) {
                        $exists = true;
                    }
                }
                if (!$exists) {
                    $datas[] = $album;
                }
            }
        } else {
            return new JsonResponse([], 404);
        }
        return $datas;
    }
}
