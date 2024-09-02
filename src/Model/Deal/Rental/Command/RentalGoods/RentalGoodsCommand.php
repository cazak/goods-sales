<?php

declare(strict_types=1);

namespace App\Model\Deal\Rental\Command\RentalGoods;

use App\Model\Deal\Rental\Entity\ValueObject\Duration;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RentalGoodsCommand
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $buyerId,
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $goodsId,
        #[Assert\Choice(callback: [Duration::class, 'casesAtString'], message: 'The duration type is not valid.')]
        #[Assert\NotBlank]
        public string $duration,
    ) {}
}
