<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Query\GetHistory;

use App\Model\Buyer\Entity\BuyerRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityNotFoundException;

final readonly class GetPurchaseHistoryQueryHandler
{
    public function __construct(
        private BuyerRepository $buyerRepository,
        private Connection $connection,
    ) {}

    /**
     * @return array<mixed>
     */
    public function __invoke(GetPurchaseHistoryQuery $query): array
    {
        $buyer = $this->buyerRepository->findById($query->buyerId);

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        return $this->connection->createQueryBuilder()
            ->select(['price', 'created_at'])
            ->from('purchase', 'p')
            ->where('p.buyer_id = :buyerId')
            ->setParameter('buyerId', $buyer->getId()->toBinary())
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
