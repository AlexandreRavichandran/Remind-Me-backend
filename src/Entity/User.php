<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ApiResource(
 *  collectionOperations={
 *      "GET": {
 *         "path":"/api/users",
 *          "normalization_context": {
 *              "groups": {"user_browse","user_add"}
 *          },
 *         "openapi_context": {
 *              "summary": "Get all users and their lists [ONLY ADMIN]",
 *              "description": "Get all users and their lists. Must be Admin to use this endpoint."
 *          }
 *       },
 *      "POST": {
 *         "path":"/api/users",
 *         "denormalization_context": {
 *              "groups": {"user_add"} 
 *         },
 *         "normalization_context": {
 *              "groups": {"user_add_response"}
 *          },
 *         "openapi_context": {
 *              "summary": "Registrate a user",
 *              "description": "Registrate a user creating a new user resource"
 *          }
 *       },
 *     },
 *  itemOperations={
 *      "GET": {
 *          "path":"/api/users/{id}",
 *          "requirements": {"id": "\d+"},
 *          "normalization_context":{
 *              "groups": {"user_read"}
 *          },
 *         "openapi_context": {
 *              "summary": "Get connected user datas",
 *              "description": "Get datas about the currently connected user, and his lists"
 *          }
 *       },
 *    },
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email",message="This email is already used in this website.")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"user_browse", "user_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Groups({"user_browse", "user_read", "user_add", "user_add_response"})
     * 
     * @Assert\NotBlank(message="You must have an email")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Groups({"user_add"})
     * 
     * @Assert\NotBlank(message="You have to set a password")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups({"user_read", "user_add", "user_add_response"})
     * 
     * @Assert\NotBlank(message="You should have a pseudonym")
     */
    private $pseudonym;

    /**
     * @ORM\OneToMany(targetEntity=UserMusicList::class, mappedBy="user", orphanRemoval=true)
     */
    private $musicList;

    /**
     * @ORM\OneToMany(targetEntity=UserMovieList::class, mappedBy="user", orphanRemoval=true)
     */
    private $movieList;

    /**
     * @ORM\OneToMany(targetEntity=UserBookList::class, mappedBy="user", orphanRemoval=true)
     */
    private $bookList;

    public function __construct()
    {
        $this->musicList = new ArrayCollection();
        $this->movieList = new ArrayCollection();
        $this->bookList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPseudonym(): ?string
    {
        return $this->pseudonym;
    }

    public function setPseudonym(string $pseudonym): self
    {
        $this->pseudonym = $pseudonym;

        return $this;
    }

    /**
     * @return Collection|UserMusicList[]
     */
    public function getMusicList(): Collection
    {
        return $this->musicList;
    }

    public function addMusicList(UserMusicList $musicList): self
    {
        if (!$this->musicList->contains($musicList)) {
            $this->musicList[] = $musicList;
            $musicList->setUser($this);
        }

        return $this;
    }

    public function removeMusicList(UserMusicList $musicList): self
    {
        if ($this->musicList->removeElement($musicList)) {
            // set the owning side to null (unless already changed)
            if ($musicList->getUser() === $this) {
                $musicList->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserMovieList[]
     */
    public function getMovieList(): Collection
    {
        return $this->movieList;
    }

    public function addMovieList(UserMovieList $movieList): self
    {
        if (!$this->movieList->contains($movieList)) {
            $this->movieList[] = $movieList;
            $movieList->setUser($this);
        }

        return $this;
    }

    public function removeMovieList(UserMovieList $movieList): self
    {
        if ($this->movieList->removeElement($movieList)) {
            // set the owning side to null (unless already changed)
            if ($movieList->getUser() === $this) {
                $movieList->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserBookList[]
     */
    public function getBookList(): Collection
    {
        return $this->bookList;
    }

    public function addBookList(UserBookList $bookList): self
    {
        if (!$this->bookList->contains($bookList)) {
            $this->bookList[] = $bookList;
            $bookList->setUser($this);
        }

        return $this;
    }

    public function removeBookList(UserBookList $bookList): self
    {
        if ($this->bookList->removeElement($bookList)) {
            // set the owning side to null (unless already changed)
            if ($bookList->getUser() === $this) {
                $bookList->setUser(null);
            }
        }

        return $this;
    }
}
