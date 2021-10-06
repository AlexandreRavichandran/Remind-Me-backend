<?php

namespace App\Entity;


use App\Entity\UserMusicList;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MusicRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MusicRepository::class)
 */
class Music
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiProperty(identifier=true)
     * @Groups({"list_music_browse","list_music_read","user_browse","user_read","list_music_add","list_music_add_response"})
     * 
     * @Assert\NotBlank(message="The music's name can't be blank.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_music_read","list_music_add","list_music_add_response"})
     * 
     * @Assert\NotBlank(message="The music must have at least 1 category.")
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_music_browse","user_browse","user_read","list_music_add","list_music_add_response"})
     * 
     * @Assert\NotBlank(message="The music must have a released date.")
     * @Assert\Regex(
     *          pattern="/\d{4}/",
     *          match="true",
     *          message="The release date must be like YYYY"
     *          )
     */
    private $releasedAt;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"list_music_browse","list_music_read","user_browse","user_read","list_music_add","list_music_add_response"})
     * @Assert\NotBlank(message="The music must be one of these types: Album, Artist or Song.")
     * @Assert\Choice(
     *          choices={"Album","Artist","Song"},
     *          message="The music must be one of these types: Album, Artist or Song."
     * )
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_music_browse","list_music_read","user_browse","user_read","list_music_add","list_music_add_response"})
     * @Assert\NotBlank(message="The music must have an artist.")
     */
    private $artist;

    /**
     * @ORM\OneToMany(targetEntity=UserMusicList::class, mappedBy="music")
     */
    private $userMusicLists;

    public function __construct()
    {
        $this->userMusicLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getReleasedAt(): string
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(string $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    public function getType(): ?string
    {
        switch ($this->type) {
            case '0':
                $type = 'Album';
                break;

            case '1':
                $type = 'Song';
                break;

            case '2':
                $type = 'Artist';
                break;

            default:
                $type = null;
                break;
        }
        return $type;
    }

    /**
     * Only 2 values possible : 'Album', 'Song'
     *
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        switch ($type) {
            case 'Album':
                $this->type = '0';
                break;

            case 'Song':
                $this->type = '1';
                break;

            case 'Artist':
                $this->type = '2';
                break;
        }
        return $this;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return Collection|UserMusicList[]
     */
    public function getUserMusicList(): Collection
    {
        return $this->userMusicLists;
    }

    public function addUserMusicList(UserMusicList $userMusicList): self
    {
        if (!$this->userMusicLists->contains($userMusicList)) {
            $this->userMusicLists[] = $userMusicList;
            $userMusicList->setMusic($this);
        }

        return $this;
    }

    public function removeUserMusicList(UserMusicList $userMusicList): self
    {
        if ($this->userMusicLists->removeElement($userMusicList)) {
            // set the owning side to null (unless already changed)
            if ($userMusicList->getMusic() === $this) {
                $userMusicList->setMusic(null);
            }
        }

        return $this;
    }
}
