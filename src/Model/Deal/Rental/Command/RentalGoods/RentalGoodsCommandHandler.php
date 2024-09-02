<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Command\RentalGoods;

use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Deal\Rental\Entity\Rental;
use App\Model\Deal\Rental\Entity\RentalRepository;
use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use App\Model\Goods\Entity\GoodsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Component\Uid\UuidV7;

final readonly class RentalGoodsCommandHandler
{
    public function __construct(
        private GoodsRepository $goodsRepository,
        private BuyerRepository $buyerRepository,
        private RentalRepository $rentalRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(RentalGoodsCommand $command): void
    {
        $goods = $this->goodsRepository->findById($command->goodsId);
        $buyer = $this->buyerRepository->findById($command->buyerId);

        if ($goods === null) {
            throw new EntityNotFoundException('Goods not found.');
        }

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        $rental = new Rental(
            new UuidV7(),
            $goods,
            $buyer,
            Duration::from($command->duration),
        );

        $buyer->pay($rental->getPrice());

        $this->entityManager->beginTransaction();

        try {
            $this->rentalRepository->save($rental);
            $this->buyerRepository->save($buyer);

            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollBack();

            throw $e;
        }
    }
}
