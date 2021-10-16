<?php

namespace App\DataProvider;

use App\Entity\BookProvider;
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
        $query = $context['filters']['q'];
        $response = $this->executeApiRequest($query);
        $datas = [];
        if ($response['totalItems'] !== 0) {
            foreach ($response['items'] as $bookData) {
                $book = new BookProvider();
                $book
                    ->setId($bookData['id'])
                    ->setApiCode($bookData['id'])
                    ->setTitle($bookData['volumeInfo']['title'])
                    ->setReleasedAt($bookData['volumeInfo']['publishedDate'])
                    ->setAuthor(join(', ', $bookData['volumeInfo']['authors']));
                if (isset($bookData['volumeInfo']['description'])) {
                    $book->setSynopsis($bookData['volumeInfo']['description']);
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
        $book->setCategory(join(', ', $response['volumeInfo']['categories']));

        return $book;
    }


    private function executeApiRequest(string $query, bool $collection = true)
    {
        if ($collection) {
            $addQuery = "?q=" . $query . "&key=" .  $_ENV['GOOGLE_BOOK_APIKEY'];
        } else {
            $addQuery = '/' . $query;
        }

        $response = $this->client->request('GET', SELF::API_COLLECTION_URL . $addQuery);

        return $response->toArray();
    }
}
