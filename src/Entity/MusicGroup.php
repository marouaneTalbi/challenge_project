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

    #[ORM\OneToMany(mappedBy: 'music_group', targetEntity: Document::class)]
    private Collection $documents;

    #[ORM\OneToMany(mappedBy: 'owner_music_group', targetEntity: Music::class)]
    private Collection $music;

    #[ORM\OneToMany(mappedBy: 'groupe', targetEntity: NewsGroup::class)]
    private Collection $newsGroups;

    public function __construct()
    {
        $this->artiste = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->music = new ArrayCollection();
        $this->newsGroups = new ArrayCollection();
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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setMusicGroup($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getMusicGroup() === $this) {
                $document->setMusicGroup(null);
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
            $music->setOwnerMusicGroup($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->music->removeElement($music)) {
            // set the owning side to null (unless already changed)
            if ($music->getOwnerMusicGroup() === $this) {
                $music->setOwnerMusicGroup(null);
            }
        }

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
            $newsGroup->setGroupe($this);
        }

        return $this;
    }

    public function removeNewsGroup(NewsGroup $newsGroup): self
    {
        if ($this->newsGroups->removeElement($newsGroup)) {
            // set the owning side to null (unless already changed)
            if ($newsGroup->getGroupe() === $this) {
                $newsGroup->setGroupe(null);
            }
        }

        return $this;
    }
}
