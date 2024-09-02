<?php

declare(strict_types=1);

namespace App\Security;

use App\Model\User\Entity\User;
use App\Model\User\Entity\ValueObject\Role;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private User $user,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return list<string>
     */
    public function getRoles(): array
    {
        return Role::casesAtString();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getEmail()->getValue();
    }

    public function eraseCredentials(): void
    {
        // nothing to do
    }

    public function getPassword(): string
    {
        return $this->user->getPassword();
    }
}
