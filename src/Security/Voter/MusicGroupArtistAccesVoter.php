<?php

namespace App\Security\Voter;

use App\Entity\MusicGroup;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MusicGroupArtistAccesVoter extends Voter
{
    const MEMBER_ACCESS = 'MEMBER_ACCESS';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'MEMBER_ACCESS' && $subject instanceof MusicGroup;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // Vérifiez si l'utilisateur est un membre du groupe
        foreach ($subject->getArtiste() as $member) {
            if ($member === $user) {
                return true;
            }
        }

        return false;
    }
}
