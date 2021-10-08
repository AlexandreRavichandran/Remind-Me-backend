<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *      collectionOperations={
 *        "GET": {
 *          "path": "/musics",
 *          "openapi_context": {
 *              "summary": "Get music datas following research parameters (type and query)",
 *              "description": "Get music datas following the type of music document and the search query.There is three types of music document: album, artist, and song",
 *              "parameters": {
 *                  {
 *                      "name": "type",
 *                      "in": "query",
 *                      "description": "the type of document to search. ONLY 3 values possible: album, artist, and song",
 *                      "required": true,
 *                      "type": "string",
 *                      "items": {"type": "string"}
 *                  },
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
 *          "path": "/musics/{apiCode}",
 *          "openapi_context": {
 *              "summary": "Get a music datas by his api code",
 *              "description": "Get a music datas by his api code.",
 *           },  
 *      }
 *  }
 * )
 */
class MusicProvider
{
    /**
     * @ApiProperty(identifier=true)
     */
    private $apiCode;
    private $title;
    private $type;
    private $artist;
    private $releasedAt;
    private $category;

    /**
     * Get the value of apiCode
     */ 
    public function getApiCode()
    {
        return $this->apiId;
    }

    /**
     * Set the value of apiCode
     *
     * @return  self
     */ 
    public function setApiCode($apiId)
    {
        $this->apiId = $apiId;

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
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of artist
     */ 
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set the value of artist
     *
     * @return  self
     */ 
    public function setArtist($artist)
    {
        $this->artist = $artist;

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
}
