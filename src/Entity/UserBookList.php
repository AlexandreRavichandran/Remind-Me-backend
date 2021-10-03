<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserBookListRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  normalizationContext={
 *      "groups" = {"user_book_list"}
 *   }
 *  )
 * @ORM\Entity(repositoryClass=UserBookListRepository::class)
 */
class UserBookList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user_book_list","user_list_show"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Listing::class, inversedBy="userBookLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_book_list"})
     */
    private $list;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="userBookLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_book_list","user_list_show"})
     */
    private $book;

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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
