<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserBookListRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "GET": {
 *         "path": "/list/books",
 *          "normalization_context": {
 *              "groups": {"list_book_browse"}
 *          }         
 *       },
 *       "POST": {
 *         "path": "/list/books",
 *          "denormalization_context": {
 *              "groups": {"list_book_add"}
 *          }         
 *       },
 *   },
 *  itemOperations={
 *      "GET": {
 *          "path":"/list/books/{id}",
 *          "requirements": {"id": "\d+"},
 *          "normalization_context":{
 *              "groups": {"list_book_read"}
 *          },
 *       },
 *      "PUT": {
 *          "path":"/list/books/{id}",
 *          "requirements": {"id": "\d+"},
 *          "denormalization_context": {
 *              "groups": {"list_book_update"}
 *              }
 *          },
 *      "DELETE": {
 *          "path":"/list/books/{id}",
 *          "requirements": {"id": "\d+"},  
 *          }
 *     },
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
     * @Groups({"list_book_browse","list_book_read","list_book_update"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Listing::class, inversedBy="userBookLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_book_browse","list_book_read"})
     */
    private $list;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="userBookLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add"})
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
