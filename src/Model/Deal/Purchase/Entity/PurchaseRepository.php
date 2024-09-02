<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Entity;

use Doctrine\ORM\EntityManagerInterface;

final readonly class PurchaseRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(Purchase $purchase): void
    {
        $this->entityManager->persist($purchase);
        $this->entityManager->flush();
    }

    public function delete(Purchase $purchase): void
    {
        $this->entityManager->remove($purchase);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Purchase
    {
        return $this->entityManager
            ->getRepository(Purchase::class)
            ->findOneBy(['id' => $id]);
    }
}
