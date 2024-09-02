<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Query\GetStatus;

use App\Model\Deal\GoodsCode\Entity\GoodsCodeRepository;
use App\Model\Deal\Rental\Entity\RentalRepository;
use Doctrine\ORM\EntityNotFoundException;

final readonly class GetRentalStatusQueryHandler
{
    public function __construct(
        private RentalRepository $rentalRepository,
        private GoodsCodeRepository $goodsCodeRepository,
    ) {}

    public function __invoke(GetRentalStatusQuery $query): RentalStatus
    {
        $rental = $this->rentalRepository->findById($query->rentalId);

        if (!$rental) {
            throw new EntityNotFoundException('Rental not found');
        }

        $goodsCode = $this->goodsCodeRepository->findByBuyerAndGoods($rental->getGoods(), $rental->getBuyer());

        if (!$goodsCode) {
            throw new EntityNotFoundException('Unique code not found');
        }

        return new RentalStatus(
            $goodsCode->getId()->toString(),
            $rental->getPrice(),
            $rental->getDuration()->value,
            $rental->getStartDate()->format('Y-m-d H:i:s'),
            $rental->getEndDate()->format('Y-m-d H:i:s'),
        );
    }
}
