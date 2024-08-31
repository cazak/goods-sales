<?php

declare(strict_types=1);

namespace App\Security;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final readonly class PasswordHasher implements PasswordHasherInterface
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {}

    public function hash(string $password): string
    {
        return $this->passwordHasherFactory->getPasswordHasher(PasswordAuthenticatedUserInterface::class)->hash($password);
    }
}
