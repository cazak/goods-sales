<?php

declare(strict_types=1);

namespace App\Model\Goods\Entity;

use Doctrine\ORM\EntityManagerInterface;

final readonly class GoodsRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(Goods $goods): void
    {
        $this->entityManager->persist($goods);
        $this->entityManager->flush();
    }

    public function delete(Goods $goods): void
    {
        $this->entityManager->remove($goods);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?Goods
    {
        return $this->entityManager
            ->getRepository(Goods::class)
            ->findOneBy(['id' => $id]);
    }
}
