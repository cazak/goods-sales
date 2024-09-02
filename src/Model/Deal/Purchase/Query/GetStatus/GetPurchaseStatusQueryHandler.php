<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Query\GetStatus;

use App\Model\Deal\GoodsCode\Entity\GoodsCodeRepository;
use App\Model\Deal\Purchase\Entity\PurchaseRepository;
use Doctrine\ORM\EntityNotFoundException;

final readonly class GetPurchaseStatusQueryHandler
{
    public function __construct(
        private PurchaseRepository $purchaseRepository,
        private GoodsCodeRepository $goodsCodeRepository,
    ) {}

    public function __invoke(GetPurchaseStatusQuery $query): PurchaseStatus
    {
        $purchase = $this->purchaseRepository->findById($query->purchaseId);

        if (!$purchase) {
            throw new EntityNotFoundException('Purchase not found');
        }

        $goodsCode = $this->goodsCodeRepository->findByBuyerAndGoods($purchase->getGoods(), $purchase->getBuyer());

        if (!$goodsCode) {
            throw new EntityNotFoundException('Unique code not found');
        }

        return new PurchaseStatus(
            $goodsCode->getId()->toString(),
            $purchase->getPrice(),
        );
    }
}
