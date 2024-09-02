<?php

declare(strict_types=1);

namespace App\Model\Goods\Command\Create;

final readonly class CreateGoodsCommand
{
    public function __construct(
        public string $name,
        public int $purchasePrice,
        public int $fourHoursPrice,
        public int $eightHoursPrice,
        public int $twelveHoursPrice,
        public int $twentyFourHoursPrice,
    ) {}
}
