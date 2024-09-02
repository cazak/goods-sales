<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Command\PurchaseGoods;

use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Deal\Purchase\Entity\Purchase;
use App\Model\Deal\Purchase\Entity\PurchaseRepository;
use App\Model\Goods\Entity\GoodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\Uid\UuidV7;

final readonly class PurchaseGoodsCommandHandler
{
    public function __construct(
        private PurchaseRepository $purchaseRepository,
        private GoodsRepository $goodsRepository,
        private BuyerRepository $buyerRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(PurchaseGoodsCommand $command): void
    {
        $goods = $this->goodsRepository->findById($command->goodsId);
        $buyer = $this->buyerRepository->findById($command->buyerId);

        if ($goods === null) {
            throw new EntityNotFoundException('Goods not found.');
        }

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        $purchase = new Purchase(
            new UuidV7(),
            $goods,
            $buyer,
        );

        $buyer->pay($purchase->getPrice());

        $this->entityManager->beginTransaction();

        try {
            $this->purchaseRepository->save($purchase);
            $this->buyerRepository->save($buyer);

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollBack();

            throw $e;
        }
    }
}
