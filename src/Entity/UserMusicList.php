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
 * normalizationContext={
 *      "groups" = {"user_music_list"}
 *   })
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
     * @Groups({"user_music_list","user_list_show"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Listing::class, inversedBy="userMusicList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_music_list"})
     */
    private $list;

    /**
     * @ORM\ManyToOne(targetEntity=Music::class, inversedBy="userMusicList")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_music_list","user_list_show"})
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
