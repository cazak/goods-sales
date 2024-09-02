<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Access\CheckBuyerAccess;

use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Deal\Rental\Entity\RentalRepository;
use Doctrine\ORM\EntityNotFoundException;

final readonly class CheckBuyerAccessHandler
{
    public function __construct(
        private BuyerRepository $buyerRepository,
        private RentalRepository $rentalRepository,
    ) {}

    public function __invoke(CheckBuyerAccess $checkBuyerAccess): bool
    {
        $buyer = $this->buyerRepository->findById($checkBuyerAccess->buyerId);
        $rental = $this->rentalRepository->findById($checkBuyerAccess->rentalId);

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        if ($rental === null) {
            throw new EntityNotFoundException('Rental not found.');
        }

        if ($buyer->getId()->toString() !== $rental->getBuyer()->getId()->toString()) {
            return false;
        }

        return true;
    }
}
