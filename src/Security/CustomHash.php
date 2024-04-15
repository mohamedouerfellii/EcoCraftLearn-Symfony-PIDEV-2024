<?php

namespace App\Security;

use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\CheckPasswordLengthTrait;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class CustomHash implements PasswordHasherInterface
{
    use CheckPasswordLengthTrait;

    public function hash(string $plainPassword): string
    {
        if ($this->isPasswordTooLong($plainPassword)) {
            throw new InvalidPasswordException();
        }

        $hashedPassword = hash('sha256', $plainPassword);

        return $hashedPassword;
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        if ('' === $plainPassword || $this->isPasswordTooLong($plainPassword)) {
            return false;
        }

        $h = hash('sha256', $plainPassword);

        return $h===$hashedPassword;
    }

    public function needsRehash(string $hashedPassword): bool
    {
        // Check if a password hash would benefit from rehashing
        return true;
    }
}