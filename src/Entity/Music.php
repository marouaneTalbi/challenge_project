<?php

namespace App\Entity;
use App\Entity\Album;
use App\Repository\MusicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicRepository::class)]
class Music
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[Assert\File(
        maxSize: '20M',
        extensions: ['mp3'],
        mimeTypes: ['audio/mpeg'],
        extensionsMessage: 'Please upload a valid mp3 file',
        maxSizeMessage: 'The mp3 is too big ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.',
        mimeTypesMessage: 'Please upload a valid mp3 file',
    )]
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $size = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'music')]
    private ?User $user_owner = null;

    #[ORM\ManyToOne(inversedBy: 'music')]
    private ?MusicGroup $owner_music_group = null;

    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'music')]
    private Collection $playlists;

    #[ORM\ManyToOne(inversedBy: 'music', cascade: ['persist'])]
    private ?Album $album = null;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUserOwner(): ?User
    {
        return $this->user_owner;
    }

    public function setUserOwner(?User $user_owner): self
    {
        $this->user_owner = $user_owner;

        return $this;
    }

    public function getOwnerMusicGroup(): ?MusicGroup
    {
        return $this->owner_music_group;
    }

    public function setOwnerMusicGroup(?MusicGroup $owner_music_group): self
    {
        $this->owner_music_group = $owner_music_group;

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
            $playlist->addMusic($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removeMusic($this);
        }

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }
}
