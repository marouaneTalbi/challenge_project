<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $name = null;

    #[ORM\Column(length: 25)]
    private ?string $manager = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'group_id')]
    private Collection $user_id;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'Group_sub')]
    private Collection $user_sub;

    #[ORM\ManyToOne(inversedBy: 'group_id')]
    private ?News $group_news = null;

    #[ORM\ManyToOne(inversedBy: 'group_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $events = null;

    public function __construct()
    {
        $this->user_name = new ArrayCollection();
        $this->user_sub = new ArrayCollection();
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

    public function getManager(): ?string
    {
        return $this->manager;
    }

    public function setManager(string $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id->add($userId);
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        $this->user_id->removeElement($userId);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserSub(): Collection
    {
        return $this->user_sub;
    }

    public function addUserSub(User $userSub): self
    {
        if (!$this->user_sub->contains($userSub)) {
            $this->user_sub->add($userSub);
            $userSub->addGroupSub($this);
        }

        return $this;
    }

    public function removeUserSub(User $userSub): self
    {
        if ($this->user_sub->removeElement($userSub)) {
            $userSub->removeGroupSub($this);
        }

        return $this;
    }

    public function getGroupNews(): ?News
    {
        return $this->group_news;
    }

    public function setGroupNews(?News $group_news): self
    {
        $this->group_news = $group_news;

        return $this;
    }

    public function getEvents(): ?Event
    {
        return $this->events;
    }

    public function setEvents(?Event $events): self
    {
        $this->events = $events;

        return $this;
    }
}
