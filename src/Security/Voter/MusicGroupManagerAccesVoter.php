<?php

namespace App\Security\Voter;

use App\Entity\MusicGroup;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MusicGroupManagerAccesVoter extends Voter
{
    const MANAGER_ACCESS = 'MANAGER_ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'MANAGER_ACCESS' && $subject instanceof MusicGroup;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }
        
        // Check if the manager is the same as the one in the group
        return $subject->getManager() === $user;
    }
}
