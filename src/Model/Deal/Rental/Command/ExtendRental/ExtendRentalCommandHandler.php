<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Command\ExtendRental;

use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Deal\Rental\Entity\RentalRepository;
use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Exception;

final readonly class ExtendRentalCommandHandler
{
    public function __construct(
        private BuyerRepository $buyerRepository,
        private RentalRepository $rentalRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(ExtendRentalCommand $command): void
    {
        $buyer = $this->buyerRepository->findById($command->buyerId);
        $rental = $this->rentalRepository->findById($command->rentalId);

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        if ($rental === null) {
            throw new EntityNotFoundException('Rental not found.');
        }

        $duration = Duration::from($command->duration);
        $paidPrice = $rental->getPrice();

        $rental->extend($duration);
        $buyer->pay($rental->getPrice() - $paidPrice);

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
