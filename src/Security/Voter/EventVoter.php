<?php

namespace App\Security\Voter;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    const EVENT_ACCESS = 'EVENT_ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'EVENT_ACCESS' && $subject instanceof Event;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        // get the current user
        $user = $token->getUser();

        // if the user is anonymous, do not grant accesss
        if (!$user instanceof User) {
            return false;
        }
        
        // check if the event is public
        if ($subject->isPublic()) {
            return true;
        }

        // check if the user is a member of the music group associated with the event
        $musicGroup = $subject->getMusicGroup();
        
        if ($musicGroup->getArtiste()->contains($user)) {
            return true;
        }

        // check if the user is the manager of the music group associated with the event
        
        $manager = $musicGroup->getManager();
        if ($manager === $user) {
            return true;
        }

        // deny access by default
        return false;
    }
}
