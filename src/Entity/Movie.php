<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**

 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_movie_add_response"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read","list_movie_add","list_movie_add_response"})
     * 
     * @Assert\NotBlank(message="The movie's title can't be blank.")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_movie_read","list_movie_add","list_movie_add_response"})
     * 
     * @Assert\NotBlank(message="The movie must have a released date.")
     * @Assert\Regex(
     *          pattern="/\d{4}/",
     *          match="false",
     *          message="The release date must be like YYYY"
     *          )
     */   
     private $releasedAt;
    /**
     * @ORM\OneToMany(targetEntity=UserMovieList::class, mappedBy="movie")
     */
    private $userMovieLists;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read","list_movie_add","list_movie_add_response"})
     * @Assert\NotBlank(message="The movie must have an API Code.")
     */
    private $apiCode;

    /**
     * @ORM\Column(type="text")
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read","list_movie_add","list_movie_add_response"})
     * @Assert\NotBlank(message="The music must have a picture url.")
     */
    private $pictureUrl;

    public function __construct()
    {
        $this->userMovieLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleasedAt(): ?string
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(string $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

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
            $userMovieList->setMovie($this);
        }

        return $this;
    }

    public function removeUserMovieList(UserMovieList $userMovieList): self
    {
        if ($this->userMovieLists->removeElement($userMovieList)) {
            // set the owning side to null (unless already changed)
            if ($userMovieList->getMovie() === $this) {
                $userMovieList->setMovie(null);
            }
        }

        return $this;
    }

    public function getApiCode(): ?string
    {
        return $this->apiCode;
    }

    public function setApiCode(string $apiCode): self
    {
        $this->apiCode = $apiCode;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }
}
