<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Access\CheckBuyerAccess;

use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Deal\Purchase\Entity\PurchaseRepository;
use Doctrine\ORM\EntityNotFoundException;

final readonly class CheckBuyerAccessHandler
{
    public function __construct(
        private BuyerRepository $buyerRepository,
        private PurchaseRepository $purchaseRepository,
    ) {}

    public function __invoke(CheckBuyerAccess $checkBuyerAccess): bool
    {
        $buyer = $this->buyerRepository->findById($checkBuyerAccess->buyerId);
        $purchase = $this->purchaseRepository->findById($checkBuyerAccess->purchaseId);

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        if ($purchase === null) {
            throw new EntityNotFoundException('Purchase not found.');
        }

        if ($buyer->getId()->toString() !== $purchase->getBuyer()->getId()->toString()) {
            return false;
        }

        return true;
    }
}
