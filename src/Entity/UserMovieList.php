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
 *          },
 *          "openapi_context": {
 *              "summary": "Get the connected user's movie list.",
 *              "description": "Get the connected user's movie list.",
 *          }         
 *       },
 *       "POST": {
 *         "path":"/list/movies",
 *          "denormalization_context": {
 *              "groups": {"list_movie_add"}
 *          },
 *          "normalization_context": {
 *              "groups": {"list_movie_add_response"}
 *          },
 *          "openapi_context": {
 *              "summary": "Add a movie on the current connected user's list",
 *              "description": "Add a movie on the current connected user's list",
 *              "requestBody": {
 *                  "content": {
 *                      "application/ld+json": {
 *                          "schema": {
 *                              "type": "object",
 *                              "properties": {
 *                                  "title": {"type": "string"},
 *                                  "realisator": {"type": "string"},
 *                                  "category": {"type": "string"},
 *                                  "releasedAt": {"type": "string"},
 *                                  "apiCode": {"type": "string"},
 * 
 *                                   }
 *                          },
 *                          "example": {
 *                               "title": "The Book of Eli",
 *                               "realisator": "Albert, Allen Hughes",
 *                               "category": "science fiction",
 *                                "releasedAt": "2010",
 *                               "apiCode": "tt1037705",
 *                          }
 *                       }
 *                  }
 *              }
 *          }         
 *       },
 *     },
 *  itemOperations={
 *      "GET": {
 *          "path":"/list/movies/{id}",
 *          "requirements": {"id": "\d+"},
 *          "normalization_context":{
 *              "groups": {"list_movie_read"}
 *          },
 *          "openapi_context": {
 *              "summary": "Get details about a movie of the connected user's movie list.",
 *              "description": "Get details about a movie of the connected user's movie list.",
 *          }
 *       },
 *      "PATCH": {
 *          "path":"/list/movies/{id}",
 *          "requirements": {"id": "\d+"},
 *          "denormalization_context": {
 *              "groups": {"list_movie_update"}
 *              },
 *          "openapi_context": {
 *              "summary": "Change order of the movie on the connected user's movie list",
 *              "description": "Change order of the movie on the connected user's movie list",
 *          }
 *       },
 *      "DELETE": {
 *          "path":"/list/movies/{id}",
 *          "requirements": {"id": "\d+"}, 
 *          "openapi_context": {
 *              "summary": "Delete a movie of the connected user's list",
 *              "description": "Delete a movie of the connected user's list",
 *          }
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
     * @Groups({"list_movie_browse","list_movie_read","list_movie_update","list_movie_add_response"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="userMovieLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read","list_movie_add","list_movie_add_response"})
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
