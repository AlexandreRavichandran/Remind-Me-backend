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
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add"})
     * 
     * @Assert\NotBlank(message="The book's name can't be blank.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_read","list_book_add"})
     * 
     * @Assert\NotBlank(message="The book's author can't be blank.")
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add"})
     * 
     * @Assert\NotBlank(message="The book must have at least 1 category.")
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     * @Groups({"list_book_read","list_book_add"})
     * 
     * @Assert\NotBlank(message="The book must have a released date.")
     * @Assert\Regex(
     *          pattern="\d{2}\/\d{2}\/\d{4}",
     *          match="false",
     *          message="The release date must be like DD/MM/YYYY"
     *          )
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
