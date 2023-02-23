<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

   
    #[Assert\Regex("/^(?=.*[a-z])(?=.*\d).{6,}$/i", message: "Password must contain at least one number and one letter")]
    #[Assert\Regex("/[A-Z]/", message: "Password must contain at least one Capital Letter")]
    #[Assert\Regex("/[!@#$%^&*()\-_=+{};:,<.>]/", message: "Password must contain at least one special character")]
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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promotionLink = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $apply = null;

    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: MusicGroup::class)]
    private Collection $managerMusicGroups;

    #[ORM\ManyToMany(targetEntity: MusicGroup::class, mappedBy: 'artiste')]
    private Collection $musicGroups;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'invite')]
    private Collection $events;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user_owner', targetEntity: Music::class)]
    private Collection $music;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Playlist::class)]
    private Collection $playlists;

    #[Assert\Image( mimeTypes: ["image/jpeg", "image/png"], mimeTypesMessage: "Please upload a valid image")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: NewsGroup::class, orphanRemoval: true)]
    private Collection $newsGroups;

    public function __construct()
    {
        $this->roles = ['ROLE_FAN'];
        $this->managerMusicGroups = new ArrayCollection();
        $this->musicGroups = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->music = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->newsGroups = new ArrayCollection();
    }

 //   public function __toString()
   // {
   //     return $this->getFirstname();
 //   }

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
        // guarantee every user at least has ROLE_FAN
        $roles[] = 'ROLE_FAN';

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPromotionLink(): ?string
    {
        return $this->promotionLink;
    }

    public function setPromotionLink(?string $promotionLink): self
    {
        $this->promotionLink = $promotionLink;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getApply(): ?string
    {
        return $this->apply;
    }

    public function setApply(?string $apply): self
    {
        $this->apply = $apply;

        return $this;
    }

    /**
     * @return Collection<int, MusicGroup>
     */
    public function getManagerMusicGroups(): Collection
    {
        return $this->managerMusicGroups;
    }

    public function addManagerMusicGroup(MusicGroup $managerMusicGroup): self
    {
        if (!$this->managerMusicGroups->contains($managerMusicGroup)) {
            $this->managerMusicGroups->add($managerMusicGroup);
            $managerMusicGroup->setManager($this);
        }

        return $this;
    }

    public function removeManagerMusicGroup(MusicGroup $managerMusicGroup): self
    {
        if ($this->managerMusicGroups->removeElement($managerMusicGroup)) {
            // set the owning side to null (unless already changed)
            if ($managerMusicGroup->getManager() === $this) {
                $managerMusicGroup->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MusicGroup>
     */
    public function getMusicGroups(): Collection
    {
        return $this->musicGroups;
    }

    public function addMusicGroup(MusicGroup $musicGroup): self
    {
        if (!$this->musicGroups->contains($musicGroup)) {
            $this->musicGroups->add($musicGroup);
            $musicGroup->addArtiste($this);
        }

        return $this;
    }

    public function removeMusicGroup(MusicGroup $musicGroup): self
    {
        if ($this->musicGroups->removeElement($musicGroup)) {
            $musicGroup->removeArtiste($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addInvite($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeInvite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setOwner($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getOwner() === $this) {
                $comment->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Music>
     */
    public function getMusic(): Collection
    {
        return $this->music;
    }

    public function addMusic(Music $music): self
    {
        if (!$this->music->contains($music)) {
            $this->music->add($music);
            $music->setUserOwner($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->music->removeElement($music)) {
            // set the owning side to null (unless already changed)
            if ($music->getUserOwner() === $this) {
                $music->setUserOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->setOwner($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            // set the owning side to null (unless already changed)
            if ($playlist->getOwner() === $this) {
                $playlist->setOwner(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }


    /**
     * @return Collection<int, NewsGroup>
     */
    public function getNewsGroups(): Collection
    {
        return $this->newsGroups;
    }

    public function addNewsGroup(NewsGroup $newsGroup): self
    {
        if (!$this->newsGroups->contains($newsGroup)) {
            $this->newsGroups->add($newsGroup);
            $newsGroup->setAuthor($this);
        }

        return $this;
    }

    public function removeNewsGroup(NewsGroup $newsGroup): self
    {
        if ($this->newsGroups->removeElement($newsGroup)) {
            // set the owning side to null (unless already changed)
            if ($newsGroup->getAuthor() === $this) {
                $newsGroup->setAuthor(null);
            }
        }

        return $this;
    }


}
