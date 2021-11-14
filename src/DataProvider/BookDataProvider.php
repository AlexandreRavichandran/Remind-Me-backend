<?php

namespace App\DataProvider;

use App\Entity\BookProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;

class BookDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface
{
    const API_COLLECTION_URL = "https://www.googleapis.com/books/v1/volumes";
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function supports(string $resourceClass, ?string $operationName = null, array $context = []): bool
    {
        return $resourceClass === BookProvider::class;
    }

    public function getCollection(string $resourceClass, ?string $operationName = null, array $context = [])
    {
        try {
            $query = $context['filters']['q'];
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => 'You must add a query "q" on your request'], Response::HTTP_BAD_REQUEST);
        }

        $response = $this->executeApiRequest($query);
        $datas = [];
        if ($response['totalItems'] !== 0) {
            foreach ($response['items'] as $bookData) {
                $book = new BookProvider();
                $book
                    ->setId($bookData['id'])
                    ->setApiCode($bookData['id'])
                    ->setTitle($bookData['volumeInfo']['title'])
                    ->setReleasedAt(substr($bookData['volumeInfo']['publishedDate'], 0, 4));
                if (isset($bookData['volumeInfo']['authors'])) {
                    $book->setAuthor(join(', ', $bookData['volumeInfo']['authors']));
                } else {
                    $book->setAuthor('inconnu');
                }
                if (isset($bookData['volumeInfo']['description'])) {
                    $book->setSynopsis($bookData['volumeInfo']['description']);
                } else {
                    $book->setAuthor('indisponible');
                }
                if (isset($bookData['volumeInfo']['imageLinks']['thumbnail'])) {
                    $book->setCoverUrl($bookData['volumeInfo']['imageLinks']['thumbnail']);
                }

                $datas[] = $book;
            }
        }
        return $datas;
    }

    public function getItem(string $resourceClass, $id, ?string $operationName = null, array $context = [])
    {
        $response = $this->executeApiRequest($id, false);
        if ($response !== null) {
            $book = new BookProvider();
            $book
                ->setApiCode($response['id'])
                ->setTitle($response['volumeInfo']['title'])
                ->setReleasedAt($response['volumeInfo']['publishedDate'])
                ->setAuthor(join(', ', $response['volumeInfo']['authors']))
                ->setSynopsis($response['volumeInfo']['description']);
            if (isset($response['volumeInfo']['imageLinks']['large'])) {
                $book->setCoverUrl($response['volumeInfo']['imageLinks']['large']);
            }
            if (isset($response['volumeInfo']['categories'])) {
                $book->setCategory(join(', ', $response['volumeInfo']['categories']));
            }

            return $book;
        } else {
            return new JsonResponse(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }
    }


    private function executeApiRequest(string $query, bool $collection = true)
    {
        if ($collection) {
            $addQuery = "?q=" . $query . "&key=" .  $_ENV['GOOGLE_BOOK_APIKEY'];
        } else {
            $addQuery = '/' . $query;
        }

        $response = $this->client->request('GET', SELF::API_COLLECTION_URL . $addQuery);
        if ($response->getStatusCode() === 503) {
            return null;
        } else {
            return $response->toArray();
        }
    }
}
