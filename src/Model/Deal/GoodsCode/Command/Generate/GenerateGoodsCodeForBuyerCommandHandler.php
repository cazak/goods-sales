<?php

declare(strict_types=1);

namespace App\Model\Deal\GoodsCode\Command\Generate;

use App\Model\Buyer\Entity\BuyerRepository;
use App\Model\Deal\DealInterface;
use App\Model\Deal\GoodsCode\Entity\GoodsCode;
use App\Model\Deal\GoodsCode\Entity\GoodsCodeRepository;
use App\Model\Deal\GoodsCode\Entity\ValueObject\DealType;
use App\Model\Deal\Purchase\Entity\PurchaseRepository;
use App\Model\Deal\Rental\Entity\RentalRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Uid\UuidV7;
use Webmozart\Assert\Assert;

final readonly class GenerateGoodsCodeForBuyerCommandHandler
{
    public function __construct(
        private GoodsCodeRepository $goodsCodeRepository,
        private RentalRepository $rentalRepository,
        private PurchaseRepository $purchaseRepository,
        private BuyerRepository $buyerRepository,
    ) {}

    public function __invoke(GenerateGoodsCodeForBuyerCommand $command): void
    {
        $buyer = $this->buyerRepository->findById($command->buyerId);

        if ($buyer === null) {
            throw new EntityNotFoundException('Buyer not found.');
        }

        $dealType = DealType::from($command->dealType);
        $deal = $this->getDeal($dealType, $command->dealId);

        $goods = $deal->getGoods();

        $goodsCode = $this->goodsCodeRepository->findByBuyerAndGoods($goods, $buyer);

        if ($goodsCode !== null) {
            return;
        }

        $goodsCode = new GoodsCode(
            new UuidV7(),
            $deal->getId(),
            $dealType,
            $goods,
            $buyer,
        );

        $this->goodsCodeRepository->save($goodsCode);
    }

    private function getDeal(DealType $dealType, string $dealId): DealInterface
    {
        $deal = match ($dealType) {
            DealType::Purchase => $this->purchaseRepository->findById($dealId),
            DealType::Rental => $this->rentalRepository->findById($dealId),
        };

        Assert::isInstanceOf($deal, DealInterface::class);

        return $deal;
    }
}
