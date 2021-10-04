<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovieRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *  collectionOperations={"GET"},
 *  itemOperations={"GET"},
 *  attributes={
 *     "order":{"name": "ASC"}
 *  }
 * ),
 * @ApiFilter(SearchFilter::class, properties= {
 * "name": "partial",
 * "realisator": "partial",
 * "category": "partial",
 * "releasedAt": "exact"
 * })
 * @ApiFilter(SearchFilter::class,properties={"name":"partial","category":"partial"})
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_movie_read"})
     */
    private $realisator;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_movie_browse","list_movie_read","user_browse","user_read"})
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_movie_read"})
     */
    private $releasedAt;

    /**
     * @ORM\OneToMany(targetEntity=UserMovieList::class, mappedBy="movie")
     */
    private $userMovieLists;

    public function __construct()
    {
        $this->userMovieLists = new ArrayCollection();
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

    public function getRealisator(): ?string
    {
        return $this->realisator;
    }

    public function setRealisator(string $realisator): self
    {
        $this->realisator = $realisator;

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
}
