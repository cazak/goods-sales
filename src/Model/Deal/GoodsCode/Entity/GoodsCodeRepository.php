<?php

declare(strict_types=1);

namespace App\Model\Deal\GoodsCode\Entity;

use App\Model\Buyer\Entity\Buyer;
use App\Model\Goods\Entity\Goods;
use Doctrine\ORM\EntityManagerInterface;

final readonly class GoodsCodeRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function save(GoodsCode $goodsCode): void
    {
        $this->entityManager->persist($goodsCode);
        $this->entityManager->flush();
    }

    public function delete(GoodsCode $goodsCode): void
    {
        $this->entityManager->remove($goodsCode);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?GoodsCode
    {
        return $this->entityManager
            ->getRepository(GoodsCode::class)
            ->findOneBy(['id' => $id]);
    }

    public function findByBuyerAndGoods(Goods $goods, Buyer $buyer): ?GoodsCode
    {
        return $this->entityManager
            ->getRepository(GoodsCode::class)
            ->findOneBy(['buyer' => $buyer, 'goods' => $goods]);
    }
}
