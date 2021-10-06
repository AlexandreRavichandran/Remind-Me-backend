<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserMovieListRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ApiResource(
 *  collectionOperations={
 *      "GET": {
 *         "path":"/list/movies",
 *          "normalization_context": {
 *              "groups": {"list_movie_browse"}
 *          }         
 *       },
 *       "POST": {
 *         "path":"/list/movies",
 *          "denormalization_context": {
 *              "groups": {"list_movie_add"}
 *          }         
 *       },
 *     },
 *  itemOperations={
 *      "GET": {
 *          "path":"/list/movies/{id}",
 *          "requirements": {"id": "\d+"},
 *          "normalization_context":{
 *              "groups": {"list_movie_read"}
 *          }
 *       },
 *      "PUT": {
 *          "path":"/list/movizes/{id}",
 *          "requirements": {"id": "\d+"},
 *          "denormalization_context": {
 *              "groups": {"list_movie_update"}
 *              }
 *       },
 *      "DELETE": {
 *          "path":"/list/movies/{id}",
 *          "requirements": {"id": "\d+"},   
 *       }
 *     },
 *  )
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
     * @Groups({"list_movie_browse","list_movie_read","list_movie_update"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="userMovieLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read","list_movie_add"})
     * @Assert\NotBlank(message="You must add movie datas")
     * @Assert\Valid
     */
    private $movie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="movieList")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="User obligatoire")
     */
    private $user;

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

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
