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

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Subscription::class, orphanRemoval: true)]
    private Collection $subscriptions;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: News::class, orphanRemoval: true)]
    private Collection $news;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $promotionLink = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $apply = null;

    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: MusicGroup::class)]
    private Collection $musicGroups;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'invite')]
    private Collection $events;

    public function __construct()
    {
        $this->roles = ['ROLE_FAN'];
        $this->subscriptions = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->musicGroups = new ArrayCollection();
        $this->events = new ArrayCollection();
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
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setUserId($this);
        }
    }
    /*
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
            $musicGroup->setManager($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getUserId() === $this) {
                $subscription->setUserId(null);
            }
        }
    }
    public function removeMusicGroup(MusicGroup $musicGroup): self
    {
        if ($this->musicGroups->removeElement($musicGroup)) {
            // set the owning side to null (unless already changed)
            if ($musicGroup->getManager() === $this) {
                $musicGroup->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news->add($news);
            $news->setUserId($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getUserId() === $this) {
                $news->setUserId(null);
            }
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
}
