<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *        "GET": {
 *          "path": "/api/books",
 *          "openapi_context": {
 *              "summary": "Get books datas following research parameters",
 *              "description": "Get book datas following the search query. A query parameter must be added to this endpoint. This query can be a book's title, author or category",
 *              "parameters": {
 *                  {
 *                      "name": "q",
 *                      "in": "query",
 *                      "description": "the query to search",
 *                      "required": true,
 *                      "type": "string",
 *                      "items": {"type": "string"}
 *                  }
 *              },  
 *          }
 *        },
 *      },
 *      itemOperations={
 *        "GET": {
 *          "path": "/api/books/{apiCode}",
 *          "openapi_context": {
 *              "summary": "Get a book datas by his api code (Google Books api_code)",
 *              "description": "Get a book by his api code. The api code must be from Google Book API",
 *              },  
 *          }
 *      }
 * )
 */
class BookProvider
{
    /**

     * @Groups({"book_list"})
     */
    private $id;

    /**
     * @param string $apiCode The code of the book to search
     * @ApiProperty(identifier=true)
     */
    public $apiCode;


    private $title;


    private $author;


    private $category;


    private $releasedAt;

    private $coverUrl;
    private $synopsis;


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of apiCode
     */
    public function getApiCode()
    {
        return $this->apiCode;
    }

    /**
     * Set the value of apiCode
     *
     * @return  self
     */
    public function setApiCode($apiCode)
    {
        $this->apiCode = $apiCode;

        return $this;
    }

    /**
     * Get the value of title
     * @ApiProperty(iri="http://schema.org/identifier")
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of releasedAt
     */
    public function getReleasedAt()
    {
        return $this->releasedAt;
    }

    /**
     * Set the value of releasedAt
     *
     * @return  self
     */
    public function setReleasedAt($releasedAt)
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    /**
     * Get the value of coverUrl
     */
    public function getCoverUrl()
    {
        return $this->coverUrl;
    }

    /**
     * Set the value of coverUrl
     *
     * @return  self
     */
    public function setCoverUrl($coverUrl)
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }

    /**
     * Get the value of synopsis
     */ 
    public function getSynopsis()
    {
        return $this->synopsis;
    }

    /**
     * Set the value of synopsis
     *
     * @return  self
     */ 
    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;

        return $this;
    }
}
