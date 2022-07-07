<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserOwnedInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserOwnedVoter extends Voter
{
    public const CAN_EDIT = 'CAN_EDIT';

    // Vérifie si le voter supporte le type d'objet demandé(les class qui implémentent l'interface UserOwnedInterface)
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CAN_EDIT])
            && $subject instanceof UserOwnedInterface;
    }

    /**
     * Undocumented function
     *
     * @param string $attribute
     * @param UserOwnedInterface $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        //récupération de l'utilisateur de l'objet
        $owner = $subject->getUser();

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CAN_EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $owner && $owner->getId() === $user->getId();
                break;
            
        }

        return false;
    }
}
