<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     collectionOperations={"GET"},
 *     itemOperations={"GET"} 
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
}
