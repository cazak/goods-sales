<?php

declare(strict_types=1);

namespace App\Model\Goods\Command\Create;

use App\Model\Goods\Entity\Goods;
use App\Model\Goods\Entity\GoodsRepository;
use App\Model\Goods\Entity\ValueObject\Price;
use Symfony\Component\Uid\UuidV7;

final readonly class CreateGoodsCommandHandler
{
    public function __construct(private GoodsRepository $goodsRepository) {}

    public function __invoke(CreateGoodsCommand $command): void
    {
        $goods = new Goods(
            new UuidV7(),
            new Price(
                $command->purchasePrice,
                $command->fourHoursPrice,
                $command->eightHoursPrice,
                $command->twelveHoursPrice,
                $command->twentyFourHoursPrice,
            ),
            $command->name,
        );

        $this->goodsRepository->save($goods);
    }
}
