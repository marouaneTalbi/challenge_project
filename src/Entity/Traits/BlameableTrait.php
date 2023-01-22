<?php

namespace App\Entity\Traits;

use App\Entity\User;
use Gedmo\Mapping\Annotation\Blameable;
use Doctrine\ORM\Mapping as ORM;

trait BlameableTrait
{
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Blameable(on: 'create')]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[Blameable(on: 'update')]
    private ?User $updatedBy = null;

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * @param User|null $createdBy
     * @return self
     */
    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    /**
     * @param User|null $updatedBy
     * @return self
     */
    public function setUpdatedBy(?User $updatedBy): self
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}