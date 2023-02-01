<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

<<<<<<< HEAD
=======
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
        $this->user_id = new ArrayCollection();
        $this->user_sub = new ArrayCollection();
    }

>>>>>>> edf448d (boite de reception des admins feature + fixs)
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
}
