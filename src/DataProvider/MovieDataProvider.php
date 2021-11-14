<?php

namespace App\DataProvider;

use App\Entity\MovieProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class MovieDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    private $client;
    const API_COLLECTION_URL = "http://www.omdbapi.com/?";
    const API_ITEM_URL = "http://www.omdbapi.com/?";

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return $resourceClass === MovieProvider::class;
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {
        try {
            $query = $context['filters']['q'];
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => 'You must add a query "q" on your request'], Response::HTTP_BAD_REQUEST);
        }

        $response = $this->executeApiRequest($query, true);
        if ($response['Response'] === "True") {
            $datas = [];
            foreach ($response['Search'] as $movieData) {
                $movie = new MovieProvider();
                $movie
                    ->setApiCode($movieData['imdbID'])
                    ->setTitle($movieData['Title'])
                    ->setReleasedAt($movieData['Year'])
                    ->setCoverUrl($movieData['Poster']);
                $datas[] = $movie;
            }
            return $datas;
        } else {
            return new JsonResponse([], 404);
        }
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        $response = $this->executeApiRequest($id, false);
        if ($response['Response'] === "True") {
            $movie = new MovieProvider();
            $movie
                ->setApiCode($response['imdbID'])
                ->setTitle($response['Title'])
                ->setReleasedAt($response['Year'])
                ->setCategory($response['Genre'])
                ->setRealisator($response['Director'])
                ->setCoverUrl($response['Poster'])
                ->setSynopsis($response['Plot'])
                ->setActors($response['Actors']);

            return $movie;
        } else {
            return new JsonResponse([], 404);
        }
    }

    private function executeApiRequest(string $query, bool $collection = true)
    {
        if ($collection) {
            $parameter = 's=';
        } else {
            $parameter = "i=";
        }
        $response = $this->client->request('GET', SELF::API_COLLECTION_URL . $parameter . $query . "&apikey=" . $_ENV['OMDB_APIKEY']);
        return $response->toArray();
    }
}
