<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations={
 *        "GET": {
 *          "path": "/movies",
 *          "openapi_context": {
 *              "summary": "Get movie datas following research parameters",
 *              "description": "Get movie datas following the search query. A query parameter must be added to this endpoint. This query can be a movie's title, realisator or category",
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
 *      }
 *  },
 *      itemOperations={
 *        "GET": {
 *          "path": "/movies/{apiCode}",
 *          "openapi_context": {
 *              "summary": "Get a movie datas by his api code (OMDB api_code)",
 *              "description": "Get a movie datas by his api code. The api code must be from OMDB API",
 *           },           
 *      }
 *  }
 * )
 */
class MovieProvider
{
    /**
     * @ApiProperty(identifier=true)
     */
    private $apiCode;
    private $title;
    private $category;
    private $realisator;
    private $releasedAt;
    private $coverUrl;
    private $synopsis;
    private $actors;


    /**
     * Get the value of apiCode
     * 
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
     * Get the value of realisator
     */
    public function getRealisator()
    {
        return $this->realisator;
    }

    /**
     * Set the value of realisator
     *
     * @return  self
     */
    public function setRealisator($realisator)
    {
        $this->realisator = $realisator;

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

    /**
     * Get the value of actors
     */ 
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Set the value of actors
     *
     * @return  self
     */ 
    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }
}
