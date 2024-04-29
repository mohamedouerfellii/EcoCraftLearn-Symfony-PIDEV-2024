<?php

namespace App\Security;

use App\Entity\Users as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->isVerified()) {
           
            throw new CustomUserMessageAccountStatusException('Your email is not verified');
        }

        if (!$user->isIsactive()) {
           
            throw new CustomUserMessageAccountStatusException('Your Account is Banned');
        }

    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

    }
}
