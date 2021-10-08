<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *      collectionOperations={"GET"},
 *      itemOperations={"GET"}
 * )
 */
class BookProvider
{
    /**
     * @ApiProperty(identifier=true)
     */
    private $apiCode;
    private $title;
    private $author;
    private $category;
    private $releasedAt;

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
}
