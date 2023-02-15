<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column]
    private ?bool $public = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $event_start = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $event_end = null;

    #[ORM\Column(length: 7)]
    private ?string $background_color = null;
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events', cascade: ['persist'])]
    private Collection $invite;
    #[ORM\Column(length: 7)]
    private ?string $border_color = null;

    #[ORM\Column(length: 7)]
    private ?string $text_color = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?MusicGroup $music_group = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $invite;

    public function __construct()
    {
        $this->invite = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getEventStart(): ?\DateTimeInterface
    {
        return $this->event_start;
    }

    public function setEventStart(\DateTimeInterface $event_start): self
    {
        $this->event_start = $event_start;

        return $this;
    }

    public function getEventEnd(): ?\DateTimeInterface
    {
        return $this->event_end;
    }

    public function setEventEnd(\DateTimeInterface $event_end): self
    {
        $this->event_end = $event_end;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->background_color;
    }

    public function setBackgroundColor(string $background_color): self
    {
        $this->background_color = $background_color;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): self
    {
        $this->text_color = $text_color;

        return $this;
    }

    public function getMusicGroup(): ?MusicGroup
    {
        return $this->music_group;
    }

    public function setMusicGroup(?MusicGroup $music_group): self
    {
        $this->music_group = $music_group;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getInvite(): Collection
    {
        return $this->invite;
    }

    public function addInvite(User $invite): self
    {
        if (!$this->invite->contains($invite)) {
            $this->invite->add($invite);
        }

        return $this;
    }

    public function removeInvite(User $invite): self
    {
        $this->invite->removeElement($invite);

        return $this;
    }
}
