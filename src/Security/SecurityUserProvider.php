<?php

declare(strict_types=1);

namespace App\Security;

use App\User\Entity\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final readonly class SecurityUserProvider implements UserProviderInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function loadUserByIdentifier(string $email): SecurityUser
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException(\sprintf('User by email %s not found.', $email));
        }

        return new SecurityUser($user);
    }

    public function refreshUser(UserInterface $user): SecurityUser
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === SecurityUser::class;
    }
}
