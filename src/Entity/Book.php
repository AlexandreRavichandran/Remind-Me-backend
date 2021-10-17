<?php

namespace App\Entity;

use App\Entity\UserBookList;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_book_add_response"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add","list_book_add_response"})
     * 
     * @Assert\NotBlank(message="The book's title can't be blank.")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_read","list_book_add","list_book_add_response"})
     * 
     * @Assert\NotBlank(message="The book's author can't be blank.")
     */
    private $author;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_book_read","list_book_add","list_book_add_response"})
     * 
     * @Assert\NotBlank(message="The book must have a released date.")
     * @Assert\Regex(
     *          pattern="/\d{4}/",
     *          match="false",
     *          message="The release date must be like YYYY"
     *          )
     */
    private $releasedAt;

    /**
     * @ORM\OneToMany(targetEntity=UserBookList::class, mappedBy="book")
     * @Groups({"book_list"})
     */
    private $userBookLists;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add","list_book_add_response"})
     * @Assert\NotBlank(message="The book must have an API Code.")
     */
    private $apiCode;

    /**
     * @ORM\Column(type="text")
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add","list_book_add_response"})
     * @Assert\NotBlank(message="The music must have a picture url.")
     */
    private $pictureUrl;

    public function __construct()
    {
        $this->userBookLists = new ArrayCollection();
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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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
