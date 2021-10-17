<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserBookListRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *  attributes={
 *      "order":{"listOrder":"asc"}
 *  },
 *  collectionOperations={
 *      "GET": {
 *         "path": "/list/books",
 *          "normalization_context": {
 *              "groups": {"list_book_browse"}
 *          },
 *          "openapi_context": {
 *              "summary": "Get the connected user's book list.",
 *              "description": "Get the connected user's book list.",
 *          }          
 *       },
 *       "POST": {
 *         "path": "/list/books",
 *          "denormalization_context": {
 *              "groups": {"list_book_add"}
 *          },
 *          "normalization_context": {
 *              "groups": {"list_book_add_response"}
 *          },
 *          "openapi_context": {
 *              "summary": "Add a book on the current connected user's list",
 *              "description": "Add a book on the current connected user's list",
 *              "requestBody": {
 *                  "content": {
 *                      "application/ld+json": {
 *                          "schema": {
 *                              "type": "object",
 *                              "properties": {
 *                                  "book": {
 *                                      "type":"object",
 *                                      "properties": {
 *                                      "title": {"type": "string"},
 *                                      "author": {"type": "string"},
 *                                      "releasedAt": {"type": "string"},
 *                                      "apiCode": {"type": "string"},
 *                                      "pictureUrl": {"type":"string"}
 *                                      }
 *                                   },
 *                              }
 *                          },
 *                          "example": {
 *                              "book": {
 *                              "title": "The Alchemist",
 *                              "author": "Paulo Coelho",
 *                              "releasedAt": "2006",
 *                              "apiCode": "FzVjBgAAQBAJ",
 *                              "pictureUrl": "link"
 *                              }   
 *                          }
 *                       }
 *                  }
 *              }
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
 *          "openapi_context": {
 *              "summary": "Get details about a book of the connected user's book list.",
 *              "description": "Get details about a book of the connected user's book list.",
 *          }
 *       },
 *      "PATCH": {
 *          "path":"/list/books/{id}",
 *          "requirements": {"id": "\d+"},
 *          "denormalization_context": {
 *              "groups": {"list_book_update"}
 *              },
 *          "openapi_context": {
 *              "summary": "Change order of the book on the connected user's book list",
 *              "description": "Change order of the book on the connected user's book list",
 *          }
 *          },
 *      "DELETE": {
 *          "path":"/list/books/{id}",
 *          "requirements": {"id": "\d+"},
 *          "openapi_context": {
 *              "summary": "Delete a book of the connected user's list",
 *              "description": "Delete a book of the connected user's list",
 *          }
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
     * @Groups({"list_book_browse","list_book_read","list_book_update","list_book_add_response"})
     */
    private $listOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="userBookLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_book_browse","list_book_read","user_browse","user_read","list_book_add","list_book_add_response"})
     * @Assert\NotBlank(message="You must add book datas")
     * @Assert\Valid
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookList")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
