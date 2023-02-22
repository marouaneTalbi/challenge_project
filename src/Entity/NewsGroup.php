<?php

namespace App\Entity;

use App\Repository\NewsGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsGroupRepository::class)]
class NewsGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $post = null;

    #[ORM\ManyToOne(inversedBy: 'newsGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'newsGroups')]
    private ?MusicGroup $groupe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?string
    {
        return $this->post;
    }

    public function setPost(string $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getGroupe(): ?MusicGroup
    {
        return $this->groupe;
    }

    public function setGroupe(?MusicGroup $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}
