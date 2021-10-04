<?php

namespace App\Entity;

use App\Entity\UserBookList;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *  collectionOperations={"GET"},
 *  itemOperations={"GET"}
 * )
 * @ApiFilter(SearchFilter::class, properties= {
 *  "name": "partial",
 *  "category": "partial",
 *  "author": "partial" 
 * })
 * 
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_read"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read"})
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_book_read"})
     */
    private $releasedAt;

    /**
     * @ORM\OneToMany(targetEntity=UserBookList::class, mappedBy="book")
     * @Groups({"book_list"})
     */
    private $userBookLists;

    public function __construct()
    {
        $this->userBookLists = new ArrayCollection();
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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
            $userBookList->setBook($this);
        }

        return $this;
    }

    public function removeUserBookList(UserBookList $userBookList): self
    {
        if ($this->userBookLists->removeElement($userBookList)) {
            // set the owning side to null (unless already changed)
            if ($userBookList->getBook() === $this) {
                $userBookList->setBook(null);
            }
        }

        return $this;
    }
}
