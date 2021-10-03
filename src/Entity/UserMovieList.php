<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserMovieListRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={
 *      "groups" = {"user_movie_list"}
 *   })
 * @ORM\Entity(repositoryClass=UserMovieListRepository::class)
 */
class UserMovieList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user_movie_list","user_list_show"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Listing::class, inversedBy="userMovieLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_movie_list"})
     */
    private $list;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="userMovieLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_movie_list","user_list_show"})
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListOrder(): ?int
    {
        return $this->listOrder;
    }

    public function setListOrder(int $listOrder): self
    {
        $this->listOrder = $listOrder;

        return $this;
    }

    public function getList(): ?Listing
    {
        return $this->list;
    }

    public function setList(?Listing $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
