<?php

declare(strict_types=1);

namespace App\Buyer\Entity;

use Doctrine\ORM\EntityManagerInterface;

final readonly class BuyerRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(Buyer $buyer): void
    {
        $this->entityManager->persist($buyer);
        $this->entityManager->flush();
    }

    public function delete(Buyer $buyer): void
    {
        $this->entityManager->remove($buyer);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Buyer
    {
        return $this->entityManager
            ->getRepository(Buyer::class)
            ->findOneBy(['id' => $id]);
    }
}
