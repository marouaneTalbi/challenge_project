<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.',)]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */

   
    // #[Assert\Regex("/^[A-Z]$/")]
        // pattern: '/^[A-Z]$/',
        // message: 'password must contain letters, numbers and special character',
 
    #[ORM\Column]
    private ?string $password = null;
    #[Assert\NotBlank]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;
    #[Assert\NotBlank]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column]
    private ?bool $isEnabled = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'user_name')]
    private Collection $group_id;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'user_sub')]
    private Collection $group_sub;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: News::class)]
    private Collection $user_news;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->group_id = new ArrayCollection();
        $this->group_sub = new ArrayCollection();
        $this->user_news = new ArrayCollection();
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupId(): Collection
    {
        return $this->group_id;
    }

    public function addGroupId(Group $groupId): self
    {
        if (!$this->group_id->contains($groupId)) {
            $this->group_id->add($groupId);
            $groupId->addUserName($this);
        }

        return $this;
    }

    public function removeGroupId(Group $groupId): self
    {
        if ($this->group_id->removeElement($groupId)) {
            $groupId->removeUserName($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroupSub(): Collection
    {
        return $this->group_sub;
    }

    public function addGroupSub(Group $groupSub): self
    {
        if (!$this->group_sub->contains($groupSub)) {
            $this->group_sub->add($groupSub);
        }

        return $this;
    }

    public function removeGroupSub(Group $groupSub): self
    {
        $this->group_sub->removeElement($groupSub);

        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getUserNews(): Collection
    {
        return $this->user_news;
    }

    public function addUserNews(News $userNews): self
    {
        if (!$this->user_news->contains($userNews)) {
            $this->user_news->add($userNews);
            $userNews->setAuthor($this);
        }

        return $this;
    }

    public function removeUserNews(News $userNews): self
    {
        if ($this->user_news->removeElement($userNews)) {
            // set the owning side to null (unless already changed)
            if ($userNews->getAuthor() === $this) {
                $userNews->setAuthor(null);
            }
        }

        return $this;
    }


}
