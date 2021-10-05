<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ListingRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ListingRepository::class)
 */
class Listing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="list", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=UserMovieList::class, mappedBy="list")
     * @Groups({"user_browse","user_read"})
     */
    private $userMovieLists;

    /**
     * @ORM\OneToMany(targetEntity=UserMusicList::class, mappedBy="list")
     * @Groups({"user_browse","user_read",})
     */
    private $userMusicLists;

    /**
     * @ORM\OneToMany(targetEntity=UserBookList::class, mappedBy="list")
     * @Groups({"user_browse","user_read"})
     */
    private $userBookLists;

    public function __construct()
    {
        $this->userMovieLists = new ArrayCollection();
        $this->userMusicLists = new ArrayCollection();
        $this->userBookLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getList() !== $this) {
            $user->setList($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|UserMovieList[]
     */
    public function getUserMovieLists(): Collection
    {
        return $this->userMovieLists;
    }

    public function addUserMovieList(UserMovieList $userMovieList): self
    {
        if (!$this->userMovieLists->contains($userMovieList)) {
            $this->userMovieLists[] = $userMovieList;
            $userMovieList->setList($this);
        }

        return $this;
    }

    public function removeUserMovieList(UserMovieList $userMovieList): self
    {
        if ($this->userMovieLists->removeElement($userMovieList)) {
            // set the owning side to null (unless already changed)
            if ($userMovieList->getList() === $this) {
                $userMovieList->setList(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|UserMusicList[]
     */
    public function getUserMusicLists(): Collection
    {
        return $this->userMusicLists;
    }

    public function addUserMusicList(UserMusicList $userMusicLists): self
    {
        if (!$this->userMusicLists->contains($userMusicLists)) {
            $this->userMusicLists[] = $userMusicLists;
            $userMusicLists->setList($this);
        }

        return $this;
    }

    public function removeUserMusicList(UserMusicList $userMusicLists): self
    {
        if ($this->userMovieLists->removeElement($userMusicLists)) {
            // set the owning side to null (unless already changed)
            if ($userMusicLists->getList() === $this) {
                $userMusicLists->setList(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserBookList[]
     */
    public function getUserBookLists(): Collection
    {
        return $this->userBookLists;
    }

    public function addUserBookList(UserBookList $userBookList): self
    {
        if (!$this->userBookLists->contains($userBookList)) {
            $this->userBookLists[] = $userBookList;
            $userBookList->setList($this);
        }

        return $this;
    }

    public function removeUserBookList(UserBookList $userBookList): self
    {
        if ($this->userBookLists->removeElement($userBookList)) {
            // set the owning side to null (unless already changed)
            if ($userBookList->getList() === $this) {
                $userBookList->setList(null);
            }
        }

        return $this;
    }
}
