<?php

declare(strict_types=1);

namespace App\Model\Deal\Purchase\Command\PurchaseGoods;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class PurchaseGoodsCommand
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $buyerId,
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $goodsId,
    ) {}
}
