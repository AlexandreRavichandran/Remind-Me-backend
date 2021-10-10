<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserMusicListRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "GET": {
 *         "path":"/list/musics",
 *          "normalization_context": {
 *              "groups": {"list_music_browse"}
 *          },
 *          "openapi_context": {
 *              "summary": "Get the connected user's music list.",
 *              "description": "Get the connected user's music list.",
 *          }
 *       },
 *       "POST": {
 *         "path": "/list/musics",
 *          "denormalization_context": {
 *              "groups": {"list_music_add"},
 *              "disable_type_enforcement"=true
 *          },
 *          "normalization_context": {
 *              "groups": {"list_music_add_response"}
 *          },
 *          "openapi_context": {
 *              "summary": "Add a music on the current connected user's list",
 *              "description": "Add a music on the current connected user's list",
 *              "requestBody": {
 *                  "content": {
 *                      "application/ld+json": {
 *                          "schema": {
 *                              "type": "object",
 *                              "properties": {
 *                                  "music": {
 *                                      "type":"object",
 *                                      "properties": {
 *                                      "title": {"type": "string"},
 *                                      "category": {"type": "string"},
 *                                      "releasedAt": {"type": "string"},
 *                                      "type": {"type": "string"},
 *                                      "artist": {"type": "string"},
 *                                      "apiCode": {"type": "string"},
 *                                      }
 *                                   },
 *                              }
 *                          },
 *                          "example": {
 *                              "music": {
 *                              "title": "Temps mort",
 *                              "category": "Hip-Hop/Rap",
 *                              "releasedAt": "2002",
 *                              "type": "Album",
 *                              "artist": "Booba",
 *                              "apiCode": "1437135238"
 *                              }   
 *                          }
 *                       }
 *                  }
 *              }
 *          } 

 *       },
 *     },
 *  itemOperations={
 *      "GET": {
 *          "path":"/list/musics/{id}",
 *          "requirements": {"id": "\d+"},
 *          "normalization_context":{
 *              "groups": {"list_music_read"}
 *          },
 *          "openapi_context": {
 *              "summary": "Get details about a music of the connected user's music list.",
 *              "description": "Get details about a music of the connected user's music list.",
 *          }
 *       },
 *      "PATCH": {
 *          "path":"/list/musics/{id}",
 *          "requirements": {"id": "\d+"},
 *          "denormalization_context": {
 *              "groups": {"list_music_update"}
 *              },
 *          "openapi_context": {
 *              "summary": "Change order of the music on the connected user's music list",
 *              "description": "Change order of the music on the connected user's music list",
 *          }
 *       },
 *      "DELETE": {
 *          "path":"/list/musics/{id}",
 *          "requirements": {"id": "\d+"},
 *          "openapi_context": {
 *              "summary": "Delete a music of the connected user's list",
 *              "description": "Delete a music of the connected user's list",
 *          }
 *         }
 *     },
 *  )
 * @ORM\Entity(repositoryClass=UserMusicListRepository::class)
 */
class UserMusicList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"list_music_browse","list_music_read","list_music_update","list_music_add_response"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Music::class, inversedBy="userMusicLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_music_browse","list_music_read","user_browse","user_read","list_music_add","list_music_add_response"})
     * @Assert\NotBlank(message="You must add music datas")
     * @Assert\Valid
     */
    private $music;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="musicList")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $user;

    public function __construct()
    {
        $this->musics = new ArrayCollection();
    }

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

    public function getMusic(): ?Music
    {
        return $this->music;
    }

    public function setMusic(?Music $music): self
    {
        $this->music = $music;

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
