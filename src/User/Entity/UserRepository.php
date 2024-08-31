<?php

declare(strict_types=1);

namespace App\User\Entity;

use Doctrine\ORM\EntityManagerInterface;

final readonly class UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email.value' => $email]);
    }
}
