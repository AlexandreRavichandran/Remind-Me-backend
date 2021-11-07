<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations={
 *        "GET": {
 *          "path": "/api/musics/albums",
 *          "openapi_context": {
 *              "summary": "Get music datas following research parameters (type and query)",
 *              "description": "Get music datas following the type of music document and the search query.There is three types of music document: album, artist, and song",
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
 *          "path": "/api/musics/albums/{apiCode}",
 *          "openapi_context": {
 *              "summary": "Get a music datas by his api code (Itunes api_code)",
 *              "description": "Get a music datas by his api code. The api code must be from Itunes API",
 *           },  
 *      }
 *  }
 * )
 */
class MusicAlbumProvider
{
    /**
     * @ApiProperty(identifier=true)
     */
    private $apiCode;
    private $title;
    private $category;
    private $tracklist;
    private $pictureUrl;
    private $artist;
    private $releasedAt;
    private $artistApiCode;

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
     * Get the value of tracklist
     */
    public function getTracklist()
    {
        return $this->tracklist;
    }

    /**
     * Set the value of tracklist
     *
     * @return  self
     */
    public function setTracklist($tracklist)
    {
        $this->tracklist = $tracklist;

        return $this;
    }

    /**
     * Get the value of pictureUrl
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * Set the value of pictureUrl
     *
     * @return  self
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;

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
     * Get the value of artistApiCode
     */ 
    public function getArtistApiCode()
    {
        return $this->artistApiCode;
    }

    /**
     * Set the value of artistApiCode
     *
     * @return  self
     */ 
    public function setArtistApiCode($artistApiCode)
    {
        $this->artistApiCode = $artistApiCode;

        return $this;
    }
}
