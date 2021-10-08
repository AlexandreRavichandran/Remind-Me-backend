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
 *          }   
 *       },
 *     },
 *  itemOperations={
 *      "GET": {
 *          "path":"/list/musics/{id}",
 *          "requirements": {"id": "\d+"},
 *          "normalization_context":{
 *              "groups": {"list_music_read"}
 *          }
 *       },
 *      "PATCH": {
 *          "path":"/list/musics/{id}",
 *          "requirements": {"id": "\d+"},
 *          "denormalization_context": {
 *              "groups": {"list_music_update"}
 *              }
 *          },
 *      "DELETE": {
 *          "path":"/list/musics/{id}",
 *          "requirements": {"id": "\d+"},
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
