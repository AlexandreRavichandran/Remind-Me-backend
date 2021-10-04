<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserMusicListRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "GET": {
 *         "path":"/list/musics",
 *          "normalization_context": {
 *              "groups": {"list_music_browse"}
 *          }         
 *       },
 *      "POST": {"path": "/list/musics"}
 *     },
 *  itemOperations={
 *      "GET": {
 *          "path":"/list/musics/{id}",
 *          "normalization_context":{
 *              "groups": {"list_music_read"}
 *          }
 *       },
 *      "PUT": {"path":"/list/musics/{id}"},
 *      "DELETE": {"path":"/list/musics/{id}"}
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
     * @Groups({"list_music_browse","list_music_read"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Listing::class, inversedBy="userMusicList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_music_browse","list_music_read"})
     */
    private $list;

    /**
     * @ORM\ManyToOne(targetEntity=Music::class, inversedBy="userMusicList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_music_browse","list_music_read","user_browse","user_read"})
     */
    private $music;

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

    public function getList(): ?Listing
    {
        return $this->list;
    }

    public function setList(?Listing $list): self
    {
        $this->list = $list;

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
}
