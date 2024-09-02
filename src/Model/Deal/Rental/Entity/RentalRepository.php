<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Entity;

use Doctrine\ORM\EntityManagerInterface;

final readonly class RentalRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(Rental $rental): void
    {
        $this->entityManager->persist($rental);
        $this->entityManager->flush();
    }

    public function delete(Rental $rental): void
    {
        $this->entityManager->remove($rental);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Rental
    {
        return $this->entityManager
            ->getRepository(Rental::class)
            ->findOneBy(['id' => $id]);
    }
}
