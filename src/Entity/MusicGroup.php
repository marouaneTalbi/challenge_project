<?php

namespace App\Entity;

use App\Repository\MusicGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicGroupRepository::class)]
class MusicGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'managerMusicGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $manager = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'musicGroups')]
    private Collection $artiste;

    #[ORM\OneToMany(mappedBy: 'music_group', targetEntity: Event::class)]
    private Collection $events;

    public function __construct()
    {
        $this->artiste = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getArtiste(): Collection
    {
        return $this->artiste;
    }

    public function addArtiste(User $artiste): self
    {
        if (!$this->artiste->contains($artiste)) {
            $this->artiste->add($artiste);
        }

        return $this;
    }

    public function removeArtiste(User $artiste): self
    {
        $this->artiste->removeElement($artiste);

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
            $event->setMusicGroup($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getMusicGroup() === $this) {
                $event->setMusicGroup(null);
            }
        }

        return $this;
    }
}
