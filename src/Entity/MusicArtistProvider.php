<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations={
 *        "GET": {
 *          "path": "/api/musics/artists",
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
 *          "path": "/api/musics/artists/{apiCode}",
 *          "openapi_context": {
 *              "summary": "Get a music datas by his api code (Itunes api_code)",
 *              "description": "Get a music datas by his api code. The api code must be from Itunes API",
 *           },  
 *      }
 *  }
 * )
 */
class MusicArtistProvider
{
    /**
     * @ApiProperty(identifier=true)
     */
    private $apiCode;
    private $name;
    private $pictureUrl;
    private $albums;

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
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Get the value of albums
     */ 
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * Set the value of albums
     *
     * @return  self
     */ 
    public function setAlbums($albums)
    {
        $this->albums = $albums;

        return $this;
    }
}
